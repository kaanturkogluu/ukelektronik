<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('sort_order')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
           
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'specs' => 'nullable',
            'features' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $validated['image'] = 'storage/' . $imagePath;
        } elseif (!empty($validated['image'])) {
            $imageUrl = trim($validated['image']);
            if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                $validated['image'] = '/' . $imageUrl;
            } else {
                $validated['image'] = $imageUrl;
            }
        } else {
            $validated['image'] = null;
        }

        // Process specs - get from request directly to avoid validation issues
        $specsInput = $request->input('specs');
        if (!empty($specsInput) && is_string($specsInput) && trim($specsInput) !== '') {
            $decoded = json_decode($specsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['specs'] = $decoded;
            } else {
                $validated['specs'] = null;
            }
        } elseif (is_array($specsInput) && !empty($specsInput)) {
            $validated['specs'] = $specsInput;
        } else {
            $validated['specs'] = null;
        }

        // Process features - get from request directly to avoid validation issues
        $featuresInput = $request->input('features');
        if (!empty($featuresInput) && is_string($featuresInput) && trim($featuresInput) !== '') {
            $decoded = json_decode($featuresInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['features'] = array_values(array_filter($decoded, fn($item) => !empty($item)));
            } else {
                $validated['features'] = null;
            }
        } elseif (is_array($featuresInput) && !empty($featuresInput)) {
            $validated['features'] = array_values(array_filter($featuresInput, fn($item) => !empty($item)));
        } else {
            $validated['features'] = null;
        }

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Remove image_file from validated array as it's not a database column
        unset($validated['image_file']);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla oluşturuldu.');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'specs' => 'nullable',
            'features' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($product->image) {
                $oldImagePath = public_path(str_replace('/', DIRECTORY_SEPARATOR, ltrim($product->image, '/')));
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
                // Also check storage path for old images
                if (str_starts_with($product->image, 'storage/') || str_starts_with($product->image, '/storage/')) {
                    $oldStoragePath = str_replace('storage/', '', $product->image);
                    $oldStoragePath = str_replace('/storage/', '', $oldStoragePath);
                    if (file_exists(storage_path('app/public/' . $oldStoragePath))) {
                        @unlink(storage_path('app/public/' . $oldStoragePath));
                    }
                }
            }
            
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $validated['image'] = 'storage/' . $imagePath;
        } elseif (!empty($validated['image'])) {
            $imageUrl = trim($validated['image']);
            if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://') && !str_starts_with($imageUrl, '/')) {
                $validated['image'] = '/' . $imageUrl;
            } else {
                $validated['image'] = $imageUrl;
            }
        } else {
            // Keep existing image if no new image provided
            $validated['image'] = $product->image;
        }

        // Process specs - get from request directly to avoid validation issues
        $specsInput = $request->input('specs');
        if (!empty($specsInput) && is_string($specsInput) && trim($specsInput) !== '') {
            $decoded = json_decode($specsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['specs'] = $decoded;
            } else {
                $validated['specs'] = null;
            }
        } elseif (is_array($specsInput) && !empty($specsInput)) {
            $validated['specs'] = $specsInput;
        } else {
            // Empty or not provided - set to null (explicitly clear)
            $validated['specs'] = null;
        }

        // Process features - get from request directly to avoid validation issues
        $featuresInput = $request->input('features');
        if (!empty($featuresInput) && is_string($featuresInput) && trim($featuresInput) !== '') {
            $decoded = json_decode($featuresInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['features'] = array_values(array_filter($decoded, fn($item) => !empty($item)));
            } else {
                $validated['features'] = null;
            }
        } elseif (is_array($featuresInput) && !empty($featuresInput)) {
            $validated['features'] = array_values(array_filter($featuresInput, fn($item) => !empty($item)));
        } else {
            // Empty or not provided - set to null (explicitly clear)
            $validated['features'] = null;
        }

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        // Remove image_file from validated array as it's not a database column
        unset($validated['image_file']);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla güncellendi.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Ürün başarıyla silindi.');
    }
}

