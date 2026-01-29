<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ImportJsonJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductJsonImportController extends Controller
{
    public function show()
    {
        return view('admin.products.import-json');
    }

    public function import(Request $request)
    {
        $request->validate([
            'json_file' => 'nullable|file|mimetypes:application/json,text/plain',
            'use_default' => 'nullable|boolean',
        ]);

        $filePath = null;

        if ($request->boolean('use_default')) {
            $filePath = base_path('urunler.json');
        } elseif ($request->hasFile('json_file')) {
            $upload = $request->file('json_file');
            $dir = storage_path('app/import-json');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filePath = $dir . '/' . Str::uuid() . '_' . $upload->getClientOriginalName();
            $upload->move($dir, basename($filePath));
        }

        if (!$filePath || !file_exists($filePath)) {
            return back()->with('error', 'JSON dosyası bulunamadı. Dosya yükleyin veya varsayılan urunler.json seçin.');
        }

        try {
            ImportJsonJob::dispatch($filePath);
            Log::info('ProductJsonImportController: Import job dispatched', ['file' => $filePath]);
        } catch (\Throwable $e) {
            Log::error('ProductJsonImportController: Dispatch failed', [
                'file' => $filePath,
                'message' => $e->getMessage(),
            ]);
            return back()->with('error', 'Kuyruğa alma sırasında hata: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'JSON içe aktarma kuyruğa alındı. ' . ImportJsonJob::CHUNK_SIZE . ' ürünlük parçalar işlenecek. Kuyruk: php artisan queue:work');
    }
}
