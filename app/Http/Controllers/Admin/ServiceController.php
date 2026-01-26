<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description_tr' => 'required|string',
            'short_description_en' => 'required|string',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'features' => 'nullable',
            'benefits' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['title'] = json_encode([
            'tr' => $validated['title_tr'],
            'en' => $validated['title_en']
        ]);
        $validated['short_description'] = json_encode([
            'tr' => $validated['short_description_tr'],
            'en' => $validated['short_description_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'],
            'en' => $validated['description_en']
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['short_description_tr'], $validated['short_description_en'], $validated['description_tr'], $validated['description_en']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('services', $imageName, 'public');
            // storeAs returns 'services/filename.jpg', we need 'storage/services/filename.jpg' for asset() function
            $validated['image'] = 'storage/' . $imagePath;
        } elseif (!empty($validated['image'])) {
            // URL ile yüklenen resim
            // Eğer http/https ile başlıyorsa olduğu gibi bırak
            // Eğer / ile başlıyorsa olduğu gibi bırak (absolute path)
            // Eğer hiçbiri değilse başına / ekle
            $imageUrl = trim($validated['image']);
            if (!str_starts_with($imageUrl, 'http://') && 
                !str_starts_with($imageUrl, 'https://') && 
                !str_starts_with($imageUrl, '/')) {
                $validated['image'] = '/' . $imageUrl;
            } else {
                $validated['image'] = $imageUrl;
            }
        } else {
            $validated['image'] = null;
        }

        // Process features and benefits
        if (isset($validated['features'])) {
            if (is_string($validated['features'])) {
                $validated['features'] = json_decode($validated['features'], true);
            }
            // Filter out empty values
            if (is_array($validated['features'])) {
                $validated['features'] = array_values(array_filter($validated['features'], fn($item) => !empty($item)));
            }
        } else {
            $validated['features'] = [];
        }

        if (isset($validated['benefits'])) {
            if (is_string($validated['benefits'])) {
                $validated['benefits'] = json_decode($validated['benefits'], true);
            }
            // Filter out empty values
            if (is_array($validated['benefits'])) {
                $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($item) => !empty($item)));
            }
        } else {
            $validated['benefits'] = [];
        }

        $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
        $baseSlug = Str::slug($titleForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Remove image_file from validated array as it's not a database field
        unset($validated['image_file']);

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Hizmet başarıyla oluşturuldu.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'short_description_tr' => 'required|string',
            'short_description_en' => 'required|string',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'features' => 'nullable',
            'benefits' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['title'] = json_encode([
            'tr' => $validated['title_tr'],
            'en' => $validated['title_en']
        ]);
        $validated['short_description'] = json_encode([
            'tr' => $validated['short_description_tr'],
            'en' => $validated['short_description_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'],
            'en' => $validated['description_en']
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['short_description_tr'], $validated['short_description_en'], $validated['description_tr'], $validated['description_en']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($service->image && (str_starts_with($service->image, 'storage/') || str_starts_with($service->image, '/storage/'))) {
                $oldImagePath = str_replace('storage/', '', $service->image);
                $oldImagePath = str_replace('/storage/', '', $oldImagePath);
                if (file_exists(storage_path('app/public/' . $oldImagePath))) {
                    @unlink(storage_path('app/public/' . $oldImagePath));
                }
            }
            
            $image = $request->file('image_file');
            $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('services', $imageName, 'public');
            $validated['image'] = 'storage/' . $imagePath;
        } elseif (!empty($validated['image'])) {
            // URL ile yüklenen resim
            $imageUrl = trim($validated['image']);
            if (!str_starts_with($imageUrl, 'http://') && 
                !str_starts_with($imageUrl, 'https://') && 
                !str_starts_with($imageUrl, '/')) {
                $validated['image'] = '/' . $imageUrl;
            } else {
                $validated['image'] = $imageUrl;
            }
        } else {
            // Keep existing image if no new image provided
            $validated['image'] = $service->image;
        }

        // Process features and benefits
        if (isset($validated['features'])) {
            if (is_string($validated['features'])) {
                $validated['features'] = json_decode($validated['features'], true);
            }
            // Filter out empty values
            if (is_array($validated['features'])) {
                $validated['features'] = array_values(array_filter($validated['features'], fn($item) => !empty($item)));
            }
        } else {
            $validated['features'] = [];
        }

        if (isset($validated['benefits'])) {
            if (is_string($validated['benefits'])) {
                $validated['benefits'] = json_decode($validated['benefits'], true);
            }
            // Filter out empty values
            if (is_array($validated['benefits'])) {
                $validated['benefits'] = array_values(array_filter($validated['benefits'], fn($item) => !empty($item)));
            }
        } else {
            $validated['benefits'] = [];
        }

        $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
        $baseSlug = Str::slug($titleForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (Service::where('slug', $slug)->where('id', '!=', $service->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        // Remove image_file from validated array as it's not a database field
        unset($validated['image_file']);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Hizmet başarıyla güncellendi.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Hizmet başarıyla silindi.');
    }
}

