<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('sort_order')->paginate(15);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/team');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/team
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/team/' . $imageName;
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
            $validated['image'] = '/img/team-1.jpg'; // Default image
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Ekip üyesi başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.show', compact('team'));
    }

    public function edit($id)
    {
        $team = Team::findOrFail($id);
        return view('admin.teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'image' => 'nullable|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'facebook' => 'nullable|url|max:255',
            'twitter' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image_file')) {
            // Delete old image if exists
            if ($team->image && file_exists(public_path($team->image)) && str_starts_with($team->image, '/img/team/')) {
                unlink(public_path($team->image));
            }

            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::slug($validated['name']) . '.' . $image->getClientOriginalExtension();
            
            // Create directory if it doesn't exist
            $publicPath = public_path('img/team');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/img/team
            $image->move($publicPath, $imageName);
            $validated['image'] = '/img/team/' . $imageName;
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
            $validated['image'] = $team->image ?? '/img/team-1.jpg';
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? $team->sort_order;

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Ekip üyesi başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        // Delete image if exists
        if ($team->image && file_exists(public_path($team->image)) && str_starts_with($team->image, '/img/team/')) {
            unlink(public_path($team->image));
        }

        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Ekip üyesi başarıyla silindi.');
    }
}
