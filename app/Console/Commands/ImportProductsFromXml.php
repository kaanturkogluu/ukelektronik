<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportProductsFromXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:import-xml {file=urunler.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from XML file to database';

    /**
     * Sanitize HTML content by removing all tags and keeping only text content
     */
    private function sanitizeHtml($html)
    {
        if (empty($html)) {
            return null;
        }
        
        // Remove all HTML tags
        $text = strip_tags($html);
        
        // Decode HTML entities
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        // Replace multiple whitespaces with single space
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Replace common HTML entities that might remain
        $text = str_replace(['&nbsp;', '&amp;', '&lt;', '&gt;', '&quot;', '&#39;'], [' ', '&', '<', '>', '"', "'"], $text);
        
        // Clean up line breaks and normalize
        $text = preg_replace('/\r\n|\r|\n/', ' ', $text);
        
        // Remove leading/trailing whitespace
        $text = trim($text);
        
        return !empty($text) ? $text : null;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("XML file not found: {$filePath}");
            return 1;
        }

        $this->info("Reading XML file: {$filePath}");
        
        // Increase memory limit for large files
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');
        
        // Try to parse XML using file stream
        libxml_use_internal_errors(true);
        
        // Use XMLReader for large files
        $reader = new \XMLReader();
        if (!$reader->open($filePath)) {
            $this->error("Failed to open XML file");
            return 1;
        }
        
        // Read until we find the root element
        while ($reader->read() && $reader->name !== 'root' && $reader->name !== 'item');
        
        // If we found root, move to first item
        if ($reader->name === 'root') {
            $reader->read();
        }

        $this->info("XML file opened successfully");
        
        // Count items first
        $totalItems = 0;
        $reader2 = new \XMLReader();
        $reader2->open($filePath);
        while ($reader2->read()) {
            if ($reader2->nodeType == \XMLReader::ELEMENT && $reader2->name === 'item') {
                $totalItems++;
            }
        }
        $reader2->close();
        
        $this->info("Found {$totalItems} items in XML");
        
        $bar = $this->output->createProgressBar($totalItems);
        $bar->start();
        
        $imported = 0;
        $skipped = 0;
        $categories = [];
        
        // Process items
        while ($reader->read()) {
            if ($reader->nodeType == \XMLReader::ELEMENT && $reader->name === 'item') {
                try {
                    // Read the entire item as XML string
                    $itemXml = $reader->readOuterXML();
                    $item = simplexml_load_string($itemXml);
                    
                    if ($item === false) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }
                    
                    $stockCode = isset($item->stockCode) ? trim((string) $item->stockCode) : '';
                    $label = isset($item->label) ? trim((string) $item->label) : '';
                    $status = isset($item->status) ? (int) $item->status : 0;
                    $mainCategory = isset($item->mainCategory) ? trim((string) $item->mainCategory) : '';
                    $picture1Path = isset($item->picture1Path) ? trim((string) $item->picture1Path) : '';
                    $details = isset($item->details) ? trim((string) $item->details) : '';
                    $specs = isset($item->specs) ? trim((string) $item->specs) : '';
                
                // Skip if no label
                if (empty($label)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                // Get or create category
                if (!empty($mainCategory)) {
                    if (!isset($categories[$mainCategory])) {
                        $category = ProductCategory::firstOrCreate(
                            ['slug' => Str::slug($mainCategory)],
                            [
                                'name' => json_encode(['tr' => $mainCategory, 'en' => $mainCategory]),
                                'description' => json_encode(['tr' => '', 'en' => '']),
                                'is_active' => true,
                                'sort_order' => 0,
                            ]
                        );
                        $categories[$mainCategory] = $category;
                    } else {
                        $category = $categories[$mainCategory];
                    }
                    $categoryId = $category->id;
                } else {
                    $categoryId = null;
                }
                
                // Generate slug from label
                $slug = Str::slug($label);
                
                // Check if product already exists
                $existingProduct = Product::where('slug', $slug)->first();
                
                // Handle image
                $image = null;
                if (!empty($picture1Path)) {
                    $image = $picture1Path;
                } else {
                    // Random placeholder image
                    $randomImage = rand(1, 6);
                    $image = "/img/img-600x400-{$randomImage}.jpg";
                }
                
                // Sanitize HTML description
                $sanitizedDescription = $this->sanitizeHtml($details);
                
                // Prepare product data
                $productData = [
                    'slug' => $slug,
                    'name' => $label,
                    'category_id' => $categoryId,
                    'image' => $image,
                    'description' => $sanitizedDescription,
                    'specs' => !empty($specs) ? json_encode([$specs]) : null,
                    'features' => null,
                    'is_active' => $status == 1,
                    'sort_order' => 0,
                ];
                
                if ($existingProduct) {
                    // Update existing product
                    $existingProduct->update($productData);
                } else {
                    // Create new product
                    Product::create($productData);
                }
                
                    $imported++;
                } catch (\Exception $e) {
                    $this->warn("\nError processing item: " . $e->getMessage());
                    $skipped++;
                }
                
                $bar->advance();
            }
        }
        
        $reader->close();
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Import completed!");
        $this->info("Imported: {$imported}");
        $this->info("Skipped: {$skipped}");
        $this->info("Total categories created/used: " . count($categories));
        
        return 0;
    }
}
