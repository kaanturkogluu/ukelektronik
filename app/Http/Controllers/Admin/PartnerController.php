<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('sort_order')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'logo' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_file')) {
            $logo = $request->file('logo_file');
            $logoName = time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
            
            $publicPath = public_path('img/partners');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            $logo->move($publicPath, $logoName);
            $validated['logo'] = '/img/partners/' . $logoName;
        } elseif (!empty($validated['logo'])) {
            $logoUrl = trim($validated['logo']);
            if (!str_starts_with($logoUrl, 'http://') && 
                !str_starts_with($logoUrl, 'https://') && 
                !str_starts_with($logoUrl, '/')) {
                $validated['logo'] = '/' . $logoUrl;
            } else {
                $validated['logo'] = $logoUrl;
            }
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        unset($validated['logo_file']);

        Partner::create($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Çözüm ortağı başarıyla eklendi.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'nullable|url|max:255',
            'logo' => 'nullable|string',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo_file')) {
            // Delete old logo if exists
            if ($partner->logo && str_starts_with($partner->logo, '/img/partners/')) {
                $oldLogoPath = public_path($partner->logo);
                if (file_exists($oldLogoPath)) {
                    @unlink($oldLogoPath);
                }
            }
            
            $logo = $request->file('logo_file');
            $logoName = time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
            
            $publicPath = public_path('img/partners');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            $logo->move($publicPath, $logoName);
            $validated['logo'] = '/img/partners/' . $logoName;
        } elseif (!empty($validated['logo'])) {
            $logoUrl = trim($validated['logo']);
            if (!str_starts_with($logoUrl, 'http://') && 
                !str_starts_with($logoUrl, 'https://') && 
                !str_starts_with($logoUrl, '/')) {
                $validated['logo'] = '/' . $logoUrl;
            } else {
                $validated['logo'] = $logoUrl;
            }
        } else {
            $validated['logo'] = $partner->logo;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? $partner->sort_order;

        unset($validated['logo_file']);

        $partner->update($validated);

        return redirect()->route('admin.partners.index')->with('success', 'Çözüm ortağı başarıyla güncellendi.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo && str_starts_with($partner->logo, '/img/partners/')) {
            $oldLogoPath = public_path($partner->logo);
            if (file_exists($oldLogoPath)) {
                @unlink($oldLogoPath);
            }
        }
        
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Çözüm ortağı başarıyla silindi.');
    }
}
