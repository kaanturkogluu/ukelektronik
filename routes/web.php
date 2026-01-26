<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\DownloadItem;
use App\Models\Team;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\App;

// Language switcher route
Route::get('/lang', function () {
    $locale = request()->get('locale', 'tr');
    if (in_array($locale, ['tr', 'en'])) {
        session(['locale' => $locale]);
        App::setLocale($locale);
    }
    $redirect = request()->get('redirect', url()->previous());
    return redirect($redirect);
})->name('lang.switch');

Route::get('/', function () {
    $services = Service::where('is_active', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();
    $projects = Project::with('category')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();
 
    $teams = Team::where('is_active', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();
    
    $categories = ProjectCategory::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    $sliders = \App\Models\Slider::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    return view('home', compact('services', 'projects', 'teams', 'categories', 'sliders'));

})->name('home');

Route::get('/about', function () {
    $teams = Team::where('is_active', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();
    return view('about', compact('teams'));
})->name('about');

Route::get('/service', function () {
    $services = Service::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    return view('service', compact('services'));
})->name('service');

Route::get('/service/{slug}', function ($slug) {
    $service = Service::where('slug', $slug)->where('is_active', true)->first();
    
    if (!$service) {
        abort(404);
    }

    // Convert to array format for view compatibility
    $serviceData = [
        'title' => $service->title,
        'icon' => $service->icon,
        'image' => $service->image ?: 'img/img-600x400-1.jpg',
        'short_description' => $service->short_description,
        'description' => $service->description,
        'features' => $service->features ?? [],
        'benefits' => $service->benefits ?? []
    ];

    return view('service-detail', ['service' => $serviceData, 'slug' => $slug]);
})->name('service.detail');

Route::get('/project', function () {
    $categorySlug = request()->query('category');
    
    $query = Project::with('category')
        ->where('is_active', true);
    
    if ($categorySlug) {
        $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
    
    $projects = $query->orderBy('sort_order')->get();
    
    $categories = ProjectCategory::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    $selectedCategory = $categorySlug ? ProjectCategory::where('slug', $categorySlug)->first() : null;
    
    // Get contact information from settings
    $address = Setting::get('address', 'Dörtyol, Hatay, Türkiye');
    
    $phonesJson = Setting::get('phones', '[]');
    $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
    if (!is_array($phones)) $phones = [];
    // Convert old format to new format if needed
    if (!empty($phones) && is_string($phones[0] ?? null)) {
        $phones = array_map(function($phone) {
            return ['number' => $phone, 'type' => 'phone'];
        }, $phones);
    }
    if (empty($phones)) $phones = [['number' => '+90 (212) 123 45 67', 'type' => 'phone']];
    
    $emailsJson = Setting::get('emails', '[]');
    $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
    if (!is_array($emails)) $emails = [];
    if (empty($emails)) $emails = ['info@ukelektronik.com'];
    
    return view('project', compact('projects', 'categories', 'selectedCategory', 'address', 'phones', 'emails'));
})->name('project');

Route::get('/project/{slug}', function ($slug) {
    $project = Project::with('category')
        ->where('slug', $slug)
        ->where('is_active', true)
        ->first();
    
    if (!$project) {
        abort(404);
    }

    // Convert to array format for view compatibility
    // Ensure details, features, and gallery are arrays
    $details = $project->details;
    if (is_string($details)) {
        $details = json_decode($details, true);
    }
    if (!is_array($details)) {
        $details = [];
    }
    
    $features = $project->features;
    if (is_string($features)) {
        $features = json_decode($features, true);
    }
    if (!is_array($features)) {
        $features = [];
    }
    
    $gallery = $project->gallery;
    if (is_string($gallery)) {
        $gallery = json_decode($gallery, true);
    }
    if (!is_array($gallery)) {
        $gallery = [];
    }
    
    $projectData = [
        'title' => $project->title,
        'category' => $project->category ? $project->category->name : '',
        'image' => $project->image,
        'description' => $project->description,
        'details' => $details,
        'features' => $features,
        'gallery' => $gallery
    ];

    return view('project-detail', ['project' => $projectData, 'slug' => $slug]);
})->name('project.detail');

Route::get('/products', function () {
    $categorySlug = request()->query('category');
    
    $query = Product::with('category')
        ->where('is_active', true);
    
    if ($categorySlug) {
        $query->whereHas('category', function($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }
    
    $products = $query->orderBy('sort_order')->paginate(20);
    
    $categories = ProductCategory::where('is_active', true)
        ->withCount(['products' => function($query) {
            $query->where('is_active', true);
        }])
        ->having('products_count', '>', 0)
        ->orderBy('sort_order')
        ->get();
    
    $selectedCategory = $categorySlug ? ProductCategory::where('slug', $categorySlug)->first() : null;
    
    return view('products', compact('products', 'categories', 'selectedCategory'));
})->name('products');

Route::get('/product/{slug}', function ($slug) {
    $product = Product::with('category')
        ->where('slug', $slug)
        ->where('is_active', true)
        ->first();
    
    if (!$product) {
        abort(404);
    }

    // Convert to array format for view compatibility
    $specs = $product->specs;
    if (is_string($specs)) {
        $specs = json_decode($specs, true);
    }
    if (!is_array($specs)) {
        $specs = [];
    }
    
    $features = $product->features;
    if (is_string($features)) {
        $features = json_decode($features, true);
    }
    if (!is_array($features)) {
        $features = [];
    }
    
    // Get product image URL (full URL for WhatsApp)
    $productImageUrl = $product->image;
    if ($productImageUrl) {
        if (str_starts_with($productImageUrl, 'http://') || str_starts_with($productImageUrl, 'https://')) {
            // Already a full URL
        } elseif (str_starts_with($productImageUrl, '/')) {
            $productImageUrl = url($productImageUrl);
        } else {
            $productImageUrl = url(asset($productImageUrl));
        }
    } else {
        $productImageUrl = url(asset('img/img-600x400-1.jpg'));
    }
    
    // Get WhatsApp number from settings
    $whatsappNumber = Setting::get('product_whatsapp', '');
    
    // Build WhatsApp message with product info and image link
    // Put image URL at the beginning for better WhatsApp preview
    $whatsappMessage = "Merhaba, *" . $product->name . "* ürünü hakkında bilgi almak istiyorum.\n\n";
    $whatsappMessage .= "*Ürün Görseli:*\n" . $productImageUrl . "\n\n";
    $whatsappMessage .= "*Ürün Adı:* " . $product->name . "\n";
    if ($product->category) {
        $whatsappMessage .= "*Kategori:* " . ($product->category->name ?? '') . "\n";
    }
    $whatsappMessage .= "*Ürün Linki:* " . url()->current() . "\n";
    if ($product->short_description) {
        // Limit description to 200 characters for WhatsApp message
        $description = mb_substr($product->short_description, 0, 200);
        if (mb_strlen($product->short_description) > 200) {
            $description .= '...';
        }
        $whatsappMessage .= "\n*Açıklama:*\n" . $description . "\n";
    }
    if (!empty($features) && count($features) > 0) {
        $whatsappMessage .= "\n*Özellikler:*\n";
        // Limit to first 5 features
        $displayFeatures = array_slice($features, 0, 5);
        foreach ($displayFeatures as $feature) {
            $whatsappMessage .= "• " . $feature . "\n";
        }
        if (count($features) > 5) {
            $whatsappMessage .= "... ve " . (count($features) - 5) . " özellik daha\n";
        }
    }
    $whatsappMessage .= "\nLütfen bu ürün hakkında detaylı bilgi verebilir misiniz?";
    
    // Build WhatsApp URL
    $whatsappUrl = '';
    if ($whatsappNumber) {
        // Clean phone number - remove spaces, dashes, parentheses
        $cleanNumber = preg_replace('/[^0-9+]/', '', $whatsappNumber);
        // Ensure it starts with country code
        if (!str_starts_with($cleanNumber, '+')) {
            if (str_starts_with($cleanNumber, '0')) {
                $cleanNumber = '+90' . substr($cleanNumber, 1);
            } else {
                $cleanNumber = '+90' . $cleanNumber;
            }
        }
        // Remove + for WhatsApp URL (only numbers)
        $phoneDigits = preg_replace('/[^0-9]/', '', $cleanNumber);
        $whatsappUrl = 'https://wa.me/' . $phoneDigits . '?text=' . urlencode($whatsappMessage);
    }
    
    // Get contact information from settings
    $phonesJson = Setting::get('phones', '[]');
    $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
    if (!is_array($phones)) $phones = [];
    if (!empty($phones) && is_string($phones[0] ?? null)) {
        $phones = array_map(function($phone) {
            return ['number' => $phone, 'type' => 'phone'];
        }, $phones);
    }
    if (empty($phones)) $phones = [['number' => '+90 (212) 123 45 67', 'type' => 'phone']];
    
    $emailsJson = Setting::get('emails', '[]');
    $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
    if (!is_array($emails)) $emails = [];
    if (empty($emails)) $emails = ['info@ukelektronik.com'];
    
    $address = Setting::get('address', 'Dörtyol, Hatay, Türkiye');
    
    // Split description into bullet points
    $descriptionItems = [];
    $description = $product->description ?: $product->short_description;
    if (!empty($description)) {
        // First, try to split by common patterns
        // Split by double newlines (paragraph breaks)
        $paragraphs = preg_split('/\n\s*\n|\r\n\s*\r\n/', $description);
        
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) continue;
            
            // Check if paragraph contains multiple sentences (split by period + space or newline)
            if (preg_match('/[\.;]\s+|\n/', $paragraph)) {
                // Split into sentences
                $sentences = preg_split('/[\.;]\s+/', $paragraph);
                foreach ($sentences as $sentence) {
                    $sentence = trim($sentence);
                    // Remove leading dashes, bullets, numbers if any
                    $sentence = preg_replace('/^[\-\•\*\d\.\)\s]+/', '', $sentence);
                    if (!empty($sentence) && strlen($sentence) > 3) {
                        $descriptionItems[] = $sentence;
                    }
                }
            } else {
                // Single item, clean it up
                $item = preg_replace('/^[\-\•\*\d\.\)\s]+/', '', $paragraph);
                if (!empty($item) && strlen($item) > 3) {
                    $descriptionItems[] = $item;
                }
            }
        }
        
        // If no items found, try splitting by single newlines
        if (empty($descriptionItems)) {
            $lines = preg_split('/[\r\n]+/', $description);
            foreach ($lines as $line) {
                $line = trim($line);
                if (!empty($line) && strlen($line) > 3) {
                    $line = preg_replace('/^[\-\•\*\d\.\)\s]+/', '', $line);
                    if (!empty($line)) {
                        $descriptionItems[] = $line;
                    }
                }
            }
        }
    }
    
    $productData = [
        'name' => $product->name,
        'category' => $product->category ? $product->category->name : '',
        'image' => $product->image ?: 'img/img-600x400-1.jpg',
        'description' => $description,
        'description_items' => $descriptionItems,
        'short_description' => $product->short_description,
        'specs' => $specs,
        'features' => $features,
        'whatsapp_url' => $whatsappUrl
    ];

    return view('product-detail', ['product' => $productData, 'phones' => $phones, 'emails' => $emails, 'address' => $address]);
})->name('product.detail');

Route::get('/datacenter', function () {
    // Get all brands (root level items with type 'brand')
    $brands = DownloadItem::where('type', 'brand')
        ->whereNull('parent_id')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    
    // Load children recursively for each brand
    $brands->load(['children' => function($query) {
        $query->where('is_active', true)->orderBy('sort_order');
    }]);
    
    // Recursively load all nested children
    $loadChildren = function($items) use (&$loadChildren) {
        foreach($items as $item) {
            if($item->children->count() > 0) {
                $item->children->load(['children' => function($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                }]);
                $loadChildren($item->children);
            }
        }
    };
    
    $loadChildren($brands);
    
    return view('datacenter', compact('brands'));
})->name('datacenter');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::get('/feature', function () {
    return view('feature');
})->name('feature');

Route::get('/quote', function () {
    return view('quote');
})->name('quote');

Route::post('/quote/save', [App\Http\Controllers\QuoteController::class, 'store'])->name('quote.save');

Route::get('/team', function () {
    $teams = Team::where('is_active', true)
        ->orderBy('sort_order')
        ->get();
    return view('team', compact('teams'));
})->name('team');

Route::get('/testimonial', function () {
    return view('testimonial');
})->name('testimonial');

Route::get('/404', function () {
    return view('404');
})->name('404');


Route::get('/faq', function () {
    $faqs = Faq::where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('id')
        ->get()
        ->map(function ($faq) {
            return [
                'question' => $faq->question,
                'answer' => $faq->answer
            ];
        })
        ->toArray();
    
    return view('faq', compact('faqs'));
})->name('faq');
