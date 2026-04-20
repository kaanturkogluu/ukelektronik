<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Path from root to this category (for breadcrumb / tree display).
     */
    public function getPathFromRoot()
    {
        $path = collect();
        $current = $this;
        while ($current) {
            $path->prepend($current);
            $current = $current->parent;
        }
        return $path;
    }

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
    
    // Override getAttribute to return translated content
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        // Translate these fields based on current locale
        if (in_array($key, ['name', 'description'])) {
            $locale = app()->getLocale();
            $translations = is_string($value) ? json_decode($value, true) : ($value ?? []);
            
            if (is_array($translations)) {
                return $translations[$locale] ?? $translations['tr'] ?? $translations['en'] ?? '';
            }
        }
        
        return $value;
    }
    
    // Get raw translations for admin forms
    protected function normalizeTranslations($value): array
    {
        // If it's already an array with language keys, return as-is (merged with defaults)
        if (is_array($value)) {
            return array_merge(['tr' => '', 'en' => ''], $value);
        }

        // If it's a JSON string, decode it (supports double-encoded JSON)
        if (is_string($value)) {
            $current = $value;
            for ($i = 0; $i < 2; $i++) {
                $decoded = json_decode($current, true);

                if (is_array($decoded)) {
                    return array_merge(['tr' => '', 'en' => ''], $decoded);
                }

                // If decoding results in a string, it was likely JSON encoded twice.
                if (is_string($decoded)) {
                    $current = $decoded;
                    continue;
                }

                break;
            }

            // Fallback: old format plain string (assume TR)
            return ['tr' => $value, 'en' => ''];
        }

        return ['tr' => '', 'en' => ''];
    }

    public function getNameTranslations()
    {
        $value = $this->attributes['name'] ?? null;
        return $this->normalizeTranslations($value);
    }
    
    public function getDescriptionTranslations()
    {
        $value = $this->attributes['description'] ?? null;
        return $this->normalizeTranslations($value);
    }
}
