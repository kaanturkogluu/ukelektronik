<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->orderBy('sort_order')->get();
        return view('admin.product-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.product-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_tr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['name'] = json_encode([
            'tr' => $validated['name_tr'],
            'en' => $validated['name_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'] ?? '',
            'en' => $validated['description_en'] ?? ''
        ]);
        
        unset($validated['name_tr'], $validated['name_en'], $validated['description_tr'], $validated['description_en']);

        $nameForSlug = json_decode($validated['name'], true)['tr'] ?? '';
        $baseSlug = Str::slug($nameForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('admin.product-categories.edit', compact('productCategory'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name_tr' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_tr' => 'nullable|string',
            'description_en' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        // Prepare translations
        $validated['name'] = json_encode([
            'tr' => $validated['name_tr'],
            'en' => $validated['name_en']
        ]);
        $validated['description'] = json_encode([
            'tr' => $validated['description_tr'] ?? '',
            'en' => $validated['description_en'] ?? ''
        ]);
        
        unset($validated['name_tr'], $validated['name_en'], $validated['description_tr'], $validated['description_en']);

        $nameForSlug = json_decode($validated['name'], true)['tr'] ?? '';
        $baseSlug = Str::slug($nameForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (ProductCategory::where('slug', $slug)->where('id', '!=', $productCategory->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->has('is_active');

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori başarıyla güncellendi.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return redirect()->route('admin.product-categories.index')->with('success', 'Kategori başarıyla silindi.');
    }
}
