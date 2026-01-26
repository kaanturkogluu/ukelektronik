<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DownloadController extends Controller
{
    public function index()
    {
        // Get all brands (root level items with type 'brand')
        $brands = DownloadItem::where('type', 'brand')
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();

        return view('admin.downloads.index', compact('brands'));
    }

    public function create(Request $request)
    {
        $parentId = $request->get('parent_id');
        $type = $request->get('type', 'category');
        
        // Get all items for parent selection
        $items = DownloadItem::orderBy('type')->orderBy('name')->get();
        
        // Get parent item if exists
        $parent = null;
        if ($parentId) {
            $parent = DownloadItem::find($parentId);
        }

        return view('admin.downloads.create', compact('items', 'parent', 'type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:brand,category,file',
            'parent_id' => 'nullable|exists:download_items,id',
            'description' => 'nullable|string',
            'file' => 'required_if:type,file|file|mimes:xlsx,xls,doc,docx,pdf|max:10240', // 10MB max
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // If type is brand, parent_id must be null
        if ($validated['type'] === 'brand') {
            $validated['parent_id'] = null;
        }

        // If type is file, handle file upload
        if ($validated['type'] === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            
            // Determine file type
            $fileType = null;
            if (in_array($extension, ['xlsx', 'xls'])) {
                $fileType = 'excel';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $fileType = 'word';
            } elseif ($extension === 'pdf') {
                $fileType = 'pdf';
            }

            // Generate unique filename
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Create directory if it doesn't exist
            $publicPath = public_path('downloads');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/downloads
            $file->move($publicPath, $fileName);
            
            $validated['file_path'] = '/downloads/' . $fileName;
            $validated['file_type'] = $fileType;
            $validated['original_filename'] = $originalName;
            $validated['file_size'] = $fileSize;
        } else {
            // For non-file types, clear file-related fields
            $validated['file_path'] = null;
            $validated['file_type'] = null;
            $validated['original_filename'] = null;
            $validated['file_size'] = null;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        DownloadItem::create($validated);

        return redirect()->route('admin.downloads.index')
            ->with('success', 'İndirme öğesi başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $item = DownloadItem::with('parent', 'children')->findOrFail($id);
        return view('admin.downloads.show', compact('item'));
    }

    public function edit($id)
    {
        $item = DownloadItem::findOrFail($id);
        $items = DownloadItem::where('id', '!=', $id)
            ->orderBy('type')
            ->orderBy('name')
            ->get();
        
        return view('admin.downloads.edit', compact('item', 'items'));
    }

    public function update(Request $request, $id)
    {
        $item = DownloadItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:brand,category,file',
            'parent_id' => 'nullable|exists:download_items,id',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:xlsx,xls,doc,docx,pdf|max:10240',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        // Prevent circular references
        if ($validated['parent_id'] == $id) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'Bir öğe kendi ebeveyni olamaz.'])
                ->withInput();
        }

        // If type is brand, parent_id must be null
        if ($validated['type'] === 'brand') {
            $validated['parent_id'] = null;
        }

        // If type is file and new file is uploaded
        if ($validated['type'] === 'file' && $request->hasFile('file')) {
            // Delete old file if exists
            if ($item->file_path && file_exists(public_path($item->file_path))) {
                unlink(public_path($item->file_path));
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileSize = $file->getSize();
            
            // Determine file type
            $fileType = null;
            if (in_array($extension, ['xlsx', 'xls'])) {
                $fileType = 'excel';
            } elseif (in_array($extension, ['doc', 'docx'])) {
                $fileType = 'word';
            } elseif ($extension === 'pdf') {
                $fileType = 'pdf';
            }

            // Generate unique filename
            $fileName = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;
            
            // Create directory if it doesn't exist
            $publicPath = public_path('downloads');
            if (!file_exists($publicPath)) {
                mkdir($publicPath, 0755, true);
            }
            
            // Move file to public/downloads
            $file->move($publicPath, $fileName);
            
            $validated['file_path'] = '/downloads/' . $fileName;
            $validated['file_type'] = $fileType;
            $validated['original_filename'] = $originalName;
            $validated['file_size'] = $fileSize;
        } elseif ($validated['type'] !== 'file') {
            // If changing from file to non-file, delete file and clear file fields
            if ($item->file_path && file_exists(public_path($item->file_path))) {
                unlink(public_path($item->file_path));
            }
            $validated['file_path'] = null;
            $validated['file_type'] = null;
            $validated['original_filename'] = null;
            $validated['file_size'] = null;
        }
        // If type is file but no new file uploaded, keep existing file data

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? $item->sort_order;

        $item->update($validated);

        return redirect()->route('admin.downloads.index')
            ->with('success', 'İndirme öğesi başarıyla güncellendi.');
    }

    public function destroy($id)
    {
        $item = DownloadItem::findOrFail($id);

        // Check if item has children
        if ($item->children()->count() > 0) {
            return redirect()->route('admin.downloads.index')
                ->with('error', 'Bu öğenin alt öğeleri var. Önce alt öğeleri silin.');
        }

        // Delete file if exists
        if ($item->file_path && file_exists(public_path($item->file_path))) {
            unlink(public_path($item->file_path));
        }

        $item->delete();

        return redirect()->route('admin.downloads.index')
            ->with('success', 'İndirme öğesi başarıyla silindi.');
    }
}
