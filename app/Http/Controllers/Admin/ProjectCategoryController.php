<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectCategoryController extends Controller
{
    public function index()
    {
        $categories = ProjectCategory::withCount('projects')->orderBy('sort_order')->get();
        return view('admin.project-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.project-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;
        while (ProjectCategory::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ProjectCategory::create($validated);

        return redirect()->route('admin.project-categories.index')->with('success', 'Proje kategorisi başarıyla oluşturuldu.');
    }

    public function edit(ProjectCategory $project_category)
    {
        return view('admin.project-categories.edit', compact('project_category'));
    }

    public function update(Request $request, ProjectCategory $project_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $nameForSlug = $validated['name'];
        $baseSlug = Str::slug($nameForSlug);
        $slug = $baseSlug;
        $counter = 1;
        while (ProjectCategory::where('slug', $slug)->where('id', '!=', $project_category->id)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $project_category->update($validated);

        return redirect()->route('admin.project-categories.index')->with('success', 'Proje kategorisi başarıyla güncellendi.');
    }

    public function destroy(ProjectCategory $project_category)
    {
        $project_category->delete();
        return redirect()->route('admin.project-categories.index')->with('success', 'Proje kategorisi başarıyla silindi.');
    }
}
