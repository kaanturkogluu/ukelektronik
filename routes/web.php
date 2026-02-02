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
    
    // WhatsApp mesajı: ürün adı ve resmi ile birlikte bilgi talebi
    $whatsappMessage = "Merhaba, Ürün Hakkında Bilgi almak istiyorum.\n\n";
    $whatsappMessage .= "*Ürün Adı:* " . $product->name . "\n";
    $whatsappMessage .= "*Ürün Görseli:* " . $productImageUrl . "\n";
    $whatsappMessage .= "*Ürün Linki:* " . url()->current();
    
    // Önce ürün WhatsApp numarası, yoksa iletişimdeki WhatsApp kullan
    $whatsappNumber = Setting::get('product_whatsapp', '');
    
    // Get contact information from settings (whatsapp_url için gerekebilir)
    $phonesJson = Setting::get('phones', '[]');
    $phones = is_string($phonesJson) ? json_decode($phonesJson, true) : [];
    if (!is_array($phones)) $phones = [];
    if (!empty($phones) && is_string($phones[0] ?? null)) {
        $phones = array_map(function($phone) {
            return ['number' => $phone, 'type' => 'phone'];
        }, $phones);
    }
    if (empty($phones)) $phones = [['number' => '+90 (212) 123 45 67', 'type' => 'phone']];
    
    // WhatsApp URL: product_whatsapp yoksa iletişimdeki ilk WhatsApp numarasını kullan
    if (empty($whatsappNumber)) {
        $whatsappEntry = collect($phones)->first(fn ($p) => ($p['type'] ?? '') === 'whatsapp');
        $whatsappNumber = $whatsappEntry['number'] ?? $phones[0]['number'] ?? '';
    }
    $whatsappUrl = '';
    if (!empty($whatsappNumber)) {
        $cleanNumber = preg_replace('/[^0-9+]/', '', $whatsappNumber);
        if (!str_starts_with($cleanNumber, '+')) {
            $cleanNumber = str_starts_with($cleanNumber, '0') ? '+90' . substr($cleanNumber, 1) : '+90' . $cleanNumber;
        }
        $phoneDigits = preg_replace('/[^0-9]/', '', $cleanNumber);
        $whatsappUrl = 'https://wa.me/' . $phoneDigits . '?text=' . urlencode($whatsappMessage);
    }
    
    $emailsJson = Setting::get('emails', '[]');
    $emails = is_string($emailsJson) ? json_decode($emailsJson, true) : [];
    if (!is_array($emails)) $emails = [];
    if (empty($emails)) $emails = ['info@ukelektronik.com'];
    
    $address = Setting::get('address', 'Dörtyol, Hatay, Türkiye');
    
    // Description: DB'de direkt HTML (tablo kodu vb.) geliyorsa liste yapma, olduğu gibi view'da HTML render edilecek
    $descriptionItems = [];
    $description = $product->description ?? '';
    // DB'de HTML entity olarak saklanmışsa gerçek HTML'e çevir, ekranda tag olarak değil içerik olarak görünsün
    if (!empty($description)) {
        $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    $isHtmlDescription = !empty($description) && preg_match('/<\s*(table|tr|td|th|div|p|br|ul|ol|li|figure|img)\b/i', $description);
    if (!empty($description) && !$isHtmlDescription) {
        // Sadece düz metin ise paragraf/cümle olarak böl
        $paragraphs = preg_split('/\n\s*\n|\r\n\s*\r\n/', $description);
        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) continue;
            if (preg_match('/[\.;]\s+|\n/', $paragraph)) {
                $sentences = preg_split('/[\.;]\s+/', $paragraph);
                foreach ($sentences as $sentence) {
                    $sentence = trim(preg_replace('/^[\-\•\*\d\.\)\s]+/', '', $sentence));
                    if (!empty($sentence) && strlen($sentence) > 3) {
                        $descriptionItems[] = $sentence;
                    }
                }
            } else {
                $item = trim(preg_replace('/^[\-\•\*\d\.\)\s]+/', '', $paragraph));
                if (!empty($item) && strlen($item) > 3) {
                    $descriptionItems[] = $item;
                }
            }
        }
        if (empty($descriptionItems)) {
            $lines = preg_split('/[\r\n]+/', $description);
            foreach ($lines as $line) {
                $line = trim(preg_replace('/^[\-\•\*\d\.\)\s]+/', '', trim($line)));
                if (!empty($line) && strlen($line) > 3) {
                    $descriptionItems[] = $line;
                }
            }
        }
    }
    
    // Açıklamadaki <figure class="image"> sarmalayıcısını kaldır, sadece içerik (img) kalsın
    if (!empty($description)) {
        $description = preg_replace('/<figure\s+class="image"\s*>([\s\S]*?)<\/figure>/iu', '$1', $description);
        // Resim linklerini düzelt: /storage/ mutlak yol subdirectory'de kırık verir, asset() ile base URL ekle
        $storageBase = rtrim(asset('storage'), '/');
        $description = preg_replace_callback('#src\s*=\s*(["\'])(/storage/[^"\']+)\1#i', function ($m) use ($storageBase) {
            return 'src=' . $m[1] . $storageBase . substr($m[2], 8) . $m[1]; // 8 = strlen('/storage')
        }, $description);
    }
    
    // Ana ürün resmi /storage/ ile başlıyorsa base URL ekle (subdirectory'de kırık link olmasın)
    $productImage = $product->image ?: 'img/img-600x400-1.jpg';
    if (str_starts_with($productImage, '/storage/')) {
        $productImage = rtrim(asset('storage'), '/') . substr($productImage, 8);
    }
    
    $productData = [
        'name' => $product->name,
        'category' => $product->category ? $product->category->name : '',
        'image' => $productImage,
        'description' => $description,
        'description_items' => $descriptionItems,
        'specs' => $specs,
        'features' => $features,
        'whatsapp_url' => $whatsappUrl
    ];

    // Benzer ürünler: aynı kategoriden 6 ürün (mevcut hariç)
    $similarProducts = Product::with('category')
        ->where('is_active', true)
        ->where('id', '!=', $product->id)
        ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
        ->inRandomOrder()
        ->limit(6)
        ->get();
    $storageBase = rtrim(asset('storage'), '/');
    $similarProducts = $similarProducts->map(function ($p) use ($storageBase) {
        $img = $p->image ?: 'img/img-600x400-1.jpg';
        if (str_starts_with($img, '/storage/')) {
            $img = $storageBase . substr($img, 8);
        }
        return [
            'name' => $p->name,
            'slug' => $p->slug,
            'image' => $img,
            'category' => $p->category ? $p->category->name : '',
        ];
    });

    return view('product-detail', [
        'product' => $productData,
        'similarProducts' => $similarProducts,
        'phones' => $phones,
        'emails' => $emails,
        'address' => $address
    ]);
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

// Enerji hesaplama geçici kapalı
Route::get('/quote', function () {
    abort(404);
    // return view('quote');
})->name('quote');

// Route::post('/quote/save', [App\Http\Controllers\QuoteController::class, 'store'])->name('quote.save');

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
