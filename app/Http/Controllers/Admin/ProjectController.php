<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('category')->orderBy('sort_order')->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = ProjectCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|exists:project_categories,id',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'details' => 'nullable',
            'features' => 'nullable',
            'gallery_urls' => 'nullable|array',
            'gallery_urls.*' => 'nullable|string',
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['title'] = json_encode([
            'tr' => $validated['title_tr'],
            'en' => $validated['title_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'],
            'en' => $validated['description_en']
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['description_tr'], $validated['description_en']);

        // Handle main image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/projects');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/projects
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/projects/' . $imageName;
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
            return redirect()->back()->withErrors(['image' => 'Ana görsel zorunludur.'])->withInput();
        }

        // Process details - get from request directly to avoid validation issues
        $detailsInput = $request->input('details');
        if (!empty($detailsInput) && is_string($detailsInput) && trim($detailsInput) !== '') {
            $decoded = json_decode($detailsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['details'] = $decoded;
            } else {
                $validated['details'] = null;
            }
        } elseif (is_array($detailsInput) && !empty($detailsInput)) {
            $validated['details'] = $detailsInput;
        } else {
            $validated['details'] = null;
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

        // Process gallery - URLs and files
        $gallery = [];
        
        // Handle gallery URLs
        if ($request->has('gallery_urls')) {
            foreach ($request->input('gallery_urls', []) as $url) {
                $url = trim($url);
                if (!empty($url)) {
                    if (!str_starts_with($url, 'http://') && 
                        !str_starts_with($url, 'https://') && 
                        !str_starts_with($url, '/')) {
                        $url = '/' . $url;
                    }
                    $gallery[] = $url;
                }
            }
        }
        
        // Handle gallery file uploads
        if ($request->hasFile('gallery_files')) {
            // Create directory if it doesn't exist
            $galleryPath = public_path('img/projects/gallery');
            if (!file_exists($galleryPath)) {
                mkdir($galleryPath, 0755, true);
            }
            
            foreach ($request->file('gallery_files') as $index => $file) {
                if ($file && $file->isValid()) {
                    $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
                    $fileName = time() . '_' . $index . '_' . Str::slug($titleForSlug) . '.' . $file->getClientOriginalExtension();
                    // Move file to public/img/projects/gallery
                    $file->move($galleryPath, $fileName);
                    $gallery[] = '/img/projects/gallery/' . $fileName;
                }
            }
        }
        
        $validated['gallery'] = !empty($gallery) ? $gallery : null;

        $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
        $baseSlug = Str::slug($titleForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Proje başarıyla oluşturuldu.');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.projects.edit', compact('project', 'categories'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'category_id' => 'required|exists:project_categories,id',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'details' => 'nullable',
            'features' => 'nullable',
            'gallery_urls' => 'nullable|array',
            'gallery_urls.*' => 'nullable|string',
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['title'] = json_encode([
            'tr' => $validated['title_tr'],
            'en' => $validated['title_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'],
            'en' => $validated['description_en']
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['description_tr'], $validated['description_en']);

        // Handle main image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($project->image) {
                $oldImagePath = public_path(str_replace('/', DIRECTORY_SEPARATOR, ltrim($project->image, '/')));
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
                // Also check storage path for old images
                if (str_starts_with($project->image, 'storage/') || str_starts_with($project->image, '/storage/')) {
                    $oldStoragePath = str_replace('storage/', '', $project->image);
                    $oldStoragePath = str_replace('/storage/', '', $oldStoragePath);
                    if (file_exists(storage_path('app/public/' . $oldStoragePath))) {
                        @unlink(storage_path('app/public/' . $oldStoragePath));
                    }
                }
            }
            
            $image = $request->file('image_file');
            $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
            $imageName = time() . '_' . Str::slug($titleForSlug) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/projects');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/projects
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/projects/' . $imageName;
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
            $validated['image'] = $project->image;
        }

        // Process details - get from request directly to avoid validation issues
        $detailsInput = $request->input('details');
        if (!empty($detailsInput) && is_string($detailsInput) && trim($detailsInput) !== '') {
            $decoded = json_decode($detailsInput, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && !empty($decoded)) {
                $validated['details'] = $decoded;
            } else {
                $validated['details'] = null;
            }
        } elseif (is_array($detailsInput) && !empty($detailsInput)) {
            $validated['details'] = $detailsInput;
        } else {
            // Empty or not provided - set to null (explicitly clear)
            $validated['details'] = null;
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

        // Process gallery - URLs and files
        $gallery = [];
        $galleryUrls = $request->input('gallery_urls', []);
        $galleryFiles = $request->file('gallery_files', []);
        
        // Process each gallery item
        $maxIndex = max(count($galleryUrls), count($galleryFiles));
        
        for ($index = 0; $index < $maxIndex; $index++) {
            // Check if there's a file upload for this index
            if (isset($galleryFiles[$index]) && $galleryFiles[$index] && $galleryFiles[$index]->isValid()) {
                // Create directory if it doesn't exist
                $galleryPath = public_path('img/projects/gallery');
                if (!file_exists($galleryPath)) {
                    mkdir($galleryPath, 0755, true);
                }
                
                $file = $galleryFiles[$index];
                $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
                $fileName = time() . '_' . $index . '_' . Str::slug($titleForSlug) . '.' . $file->getClientOriginalExtension();
                // Move file to public/img/projects/gallery
                $file->move($galleryPath, $fileName);
                $gallery[] = '/img/projects/gallery/' . $fileName;
            } elseif (isset($galleryUrls[$index])) {
                // Use URL if no file upload
                $url = trim($galleryUrls[$index]);
                if (!empty($url)) {
                    if (!str_starts_with($url, 'http://') && 
                        !str_starts_with($url, 'https://') && 
                        !str_starts_with($url, '/')) {
                        $url = '/' . $url;
                    }
                    // Avoid duplicates
                    if (!in_array($url, $gallery)) {
                        $gallery[] = $url;
                    }
                }
            }
        }
        
        // Remove duplicates and empty values
        $gallery = array_values(array_unique(array_filter($gallery)));
        
        // If gallery is empty, set to null, otherwise use the new gallery
        $validated['gallery'] = !empty($gallery) ? $gallery : null;

        $titleForSlug = json_decode($validated['title'], true)['tr'] ?? '';
        $baseSlug = Str::slug($titleForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (Project::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Proje başarıyla güncellendi.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proje başarıyla silindi.');
    }
}

