<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('sort_order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text_tr' => 'nullable|string|max:255',
            'button_text_en' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
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
        $validated['button_text'] = json_encode([
            'tr' => $validated['button_text_tr'] ?? '',
            'en' => $validated['button_text_en'] ?? ''
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['description_tr'], $validated['description_en'], $validated['button_text_tr'], $validated['button_text_en']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/sliders');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/sliders
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/sliders/' . $imageName;
        } elseif (!empty($validated['image'])) {
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

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        // Remove image_file from validated array as it's not a database field
        unset($validated['image_file']);

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider başarıyla oluşturuldu.');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title_tr' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_tr' => 'required|string',
            'description_en' => 'required|string',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'button_text_tr' => 'nullable|string|max:255',
            'button_text_en' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',
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
        $validated['button_text'] = json_encode([
            'tr' => $validated['button_text_tr'] ?? '',
            'en' => $validated['button_text_en'] ?? ''
        ]);
        
        unset($validated['title_tr'], $validated['title_en'], $validated['description_tr'], $validated['description_en'], $validated['button_text_tr'], $validated['button_text_en']);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($slider->image && str_starts_with($slider->image, '/img/sliders/')) {
                $oldImagePath = public_path($slider->image);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/sliders');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/sliders
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/sliders/' . $imageName;
        } elseif (!empty($validated['image'])) {
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
            $validated['image'] = $slider->image;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $slider->sort_order;

        // Remove image_file from validated array as it's not a database field
        unset($validated['image_file']);

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Slider başarıyla güncellendi.');
    }

    public function destroy(Slider $slider)
    {
        // Delete image if exists
        if ($slider->image && str_starts_with($slider->image, '/img/sliders/')) {
            $oldImagePath = public_path($slider->image);
            if (file_exists($oldImagePath)) {
                @unlink($oldImagePath);
            }
        }
        
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider başarıyla silindi.');
    }
}
