<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'icon',
        'image',
        'short_description',
        'description',
        'features',
        'benefits',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'short_description' => 'array',
        'description' => 'array',
        'features' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
    ];
    
    // Override getAttribute to return translated content
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        // Translate these fields based on current locale
        if (in_array($key, ['title', 'short_description', 'description'])) {
            $locale = app()->getLocale();
            $translations = is_string($value) ? json_decode($value, true) : ($value ?? []);
            
            if (is_array($translations)) {
                return $translations[$locale] ?? $translations['tr'] ?? $translations['en'] ?? '';
            }
        }
        
        return $value;
    }
    
    // Get raw translations for admin forms
    public function getTitleTranslations()
    {
        $value = $this->attributes['title'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
    
    public function getShortDescriptionTranslations()
    {
        $value = $this->attributes['short_description'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
    
    public function getDescriptionTranslations()
    {
        $value = $this->attributes['description'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
}
