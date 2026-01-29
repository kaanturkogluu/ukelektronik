<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductJsonImporter
{
    /**
     * Process a chunk of items (arrays in the format from urunler.json).
     * Each item: stock_code, title, brand, mainCategory, category, subCategory, stockAmount, details, picture1Path..picture4Path.
     *
     * @param array<int, array<string, mixed>> $items
     * @return array{created: int, updated: int, skipped: int}
     */
    public function processChunkFromItemArrays(array $items): array
    {
        $brandCache = [];
        $categoryCache = [];
        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($items as $item) {
            $stockCode = trim((string) ($item['stock_code'] ?? ''));
            $title = trim((string) ($item['title'] ?? ''));

            if ($stockCode === '' || $title === '') {
                $skipped++;
                continue;
            }

            $brandName = trim((string) ($item['brand'] ?? ''));
            $brandId = $this->getOrCreateBrandId($brandName, $brandCache);

            $mainCategory = trim((string) ($item['mainCategory'] ?? ''));
            $category = trim((string) ($item['category'] ?? ''));
            $subCategory = trim((string) ($item['subCategory'] ?? ''));
            $categoryId = $this->getOrCreateCategoryPath(
                [$mainCategory, $category, $subCategory],
                $categoryCache
            );

            $stockAmount = (int) ($item['stockAmount'] ?? 0);
            $details = trim((string) ($item['details'] ?? ''));
            if ($details !== '') {
                $details = $this->extractAndStoreBase64ImagesFromHtml($details, $stockCode);
            }

            $picturePaths = [];
            foreach (['picture1Path', 'picture2Path', 'picture3Path', 'picture4Path'] as $key) {
                $path = trim((string) ($item[$key] ?? ''));
                if ($path !== '') {
                    $stored = $this->normalizeAndStoreImage($path, $stockCode, $key);
                    if ($stored !== null) {
                        $picturePaths[] = $stored;
                    }
                }
            }

            $slug = Str::slug($title) ?: Str::slug($stockCode);
            $existing = Product::where('stock_code', $stockCode)->first();
            $payload = [
                'stock_code' => $stockCode,
                'slug' => $this->ensureUniqueSlug($slug, $existing?->id),
                'name' => $title,
                'category_id' => $categoryId,
                'brand_id' => $brandId,
                'stock_amount' => max(0, $stockAmount),
                'description' => $details !== '' ? $details : null,
                'image' => $picturePaths[0] ?? null,
                'images' => $picturePaths !== [] ? $picturePaths : null,
                'is_active' => true,
                'sort_order' => 0,
            ];

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                Product::create($payload);
                $created++;
            }
        }

        return ['created' => $created, 'updated' => $updated, 'skipped' => $skipped];
    }

    private function getOrCreateBrandId(string $brandName, array &$cache): ?int
    {
        $brandName = trim($brandName);
        if ($brandName === '') {
            return null;
        }
        $key = mb_strtolower($brandName);
        if (!isset($cache[$key])) {
            $brand = Brand::firstOrCreate(
                ['name' => $brandName],
                ['slug' => Str::slug($brandName)]
            );
            $cache[$key] = $brand->id;
        }
        return $cache[$key];
    }

    /**
     * Build category tree: mainCategory -> category -> subCategory. Returns leaf category id.
     */
    private function getOrCreateCategoryPath(array $parts, array &$cache): ?int
    {
        $parentId = null;
        $parentSlug = '';
        $lastId = null;
        $hasParentId = Schema::hasColumn('product_categories', 'parent_id');

        foreach ($parts as $raw) {
            $name = trim((string) $raw);
            if ($name === '') {
                continue;
            }

            $baseSlug = Str::slug($name);
            $fullSlugBase = $parentSlug ? ($parentSlug . '-' . $baseSlug) : $baseSlug;
            $slug = $fullSlugBase;

            if (!isset($cache[$slug])) {
                $existing = ProductCategory::where('slug', $slug)->first();
                if ($existing) {
                    // Fix tree if parent_id exists and mismatched (common after previous duplicate logic)
                    if ($hasParentId && $existing->parent_id !== $parentId) {
                        $existing->parent_id = $parentId;
                        $existing->save();
                    }
                    $cache[$slug] = $existing->id;
                } else {
                    $data = [
                        'name' => ['tr' => $name, 'en' => $name],
                        'description' => ['tr' => '', 'en' => ''],
                        'slug' => $slug,
                        'is_active' => true,
                        'sort_order' => 0,
                    ];
                    if ($hasParentId) {
                        $data['parent_id'] = $parentId;
                    }
                    $cat = ProductCategory::create($data);
                    $cache[$slug] = $cat->id;
                }
            }

            $parentId = $cache[$slug];
            $parentSlug = $slug;
            $lastId = $parentId;
        }

        return $lastId;
    }

    private function ensureUniqueSlug(string $slug, ?int $excludeProductId = null): string
    {
        $base = $slug;
        $counter = 1;
        while (true) {
            $q = Product::where('slug', $slug);
            if ($excludeProductId !== null) {
                $q->where('id', '!=', $excludeProductId);
            }
            if (!$q->exists()) {
                break;
            }
            $slug = $base . '-' . $counter++;
        }
        return $slug;
    }

    /**
     * If the value is a base64 image (data URI or raw), store it on disk and return a public path.
     * Otherwise return the original URL/path.
     *
     * This prevents max_allowed_packet errors when base64 strings are inserted into MySQL.
     */
    private function normalizeAndStoreImage(string $value, string $stockCode, string $key): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        // URLs / already-stored paths
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, '/')) {
            return $value;
        }

        // data:image/<ext>;base64,<...>
        if (preg_match('#^data:image/([a-zA-Z0-9\\+\\-\\.]+);base64,(.*)$#s', $value, $m)) {
            $ext = strtolower($m[1]);
            $b64 = $m[2];
            $bin = base64_decode($b64, true);
            if ($bin === false) {
                Log::warning('ProductJsonImporter: Invalid base64 image (data-uri)', [
                    'stock_code' => $stockCode,
                    'field' => $key,
                ]);
                return null;
            }
            return $this->storeBinaryImage($bin, $stockCode, $ext);
        }

        // raw base64 (heuristic): big string with only base64 chars
        if (strlen($value) > 5000 && preg_match('#^[A-Za-z0-9+/=\\s]+$#', $value)) {
            $bin = base64_decode($value, true);
            if ($bin === false) {
                return null;
            }
            return $this->storeBinaryImage($bin, $stockCode, 'jpg');
        }

        // otherwise assume it's a relative URL/path
        return $value;
    }

    private function storeBinaryImage(string $binary, string $stockCode, string $ext): string
    {
        $ext = preg_replace('/[^a-z0-9]/i', '', $ext) ?: 'jpg';
        $relative = 'product-images/' . Str::slug($stockCode) . '/' . Str::random(16) . '.' . $ext;
        Storage::disk('public')->put($relative, $binary);

        // Return a web path usable inside HTML (e.g. <img src="...">)
        return '/storage/' . $relative;
    }

    /**
     * Find data-uri base64 images inside HTML (e.g. <img src="data:image/png;base64,...">),
     * store them to disk, and replace src with /storage/... URL.
     */
    private function extractAndStoreBase64ImagesFromHtml(string $html, string $stockCode): string
    {
        $i = 0;
        return preg_replace_callback(
            '#src\\s*=\\s*(\"|\\\')\\s*data:image/([a-zA-Z0-9\\+\\-\\.]+);base64,([^\"\\\']+)\\s*\\1#s',
            function ($m) use ($stockCode, &$i) {
                $ext = strtolower($m[2]);
                $b64 = $m[3];
                $bin = base64_decode($b64, true);
                if ($bin === false) {
                    return $m[0];
                }
                // Slightly vary stockCode so multiple inline images don't overwrite
                $path = $this->storeBinaryImage($bin, $stockCode . '-d' . (++$i), $ext);
                $quote = $m[1];
                return 'src=' . $quote . $path . $quote;
            },
            $html
        ) ?? $html;
    }
}
