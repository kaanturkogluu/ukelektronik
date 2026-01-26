<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'parent_id',
        'file_path',
        'file_type',
        'original_filename',
        'file_size',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer',
    ];

    // Self-referencing relationship - parent
    public function parent()
    {
        return $this->belongsTo(DownloadItem::class, 'parent_id');
    }

    // Self-referencing relationship - children
    public function children()
    {
        return $this->hasMany(DownloadItem::class, 'parent_id')->orderBy('sort_order');
    }

    // Get all descendants recursively
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // Check if item is a file
    public function isFile()
    {
        return $this->type === 'file';
    }

    // Check if item is a brand
    public function isBrand()
    {
        return $this->type === 'brand';
    }

    // Check if item is a category
    public function isCategory()
    {
        return $this->type === 'category';
    }

    // Get file extension
    public function getFileExtensionAttribute()
    {
        if ($this->file_path) {
            return pathinfo($this->file_path, PATHINFO_EXTENSION);
        }
        return null;
    }

    // Get formatted file size
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
