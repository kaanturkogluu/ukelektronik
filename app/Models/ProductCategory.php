<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

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
    public function getNameTranslations()
    {
        $value = $this->attributes['name'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
    
    public function getDescriptionTranslations()
    {
        $value = $this->attributes['description'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
}
