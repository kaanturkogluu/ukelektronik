<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'button_text',
        'button_link',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'button_text' => 'array',
        'is_active' => 'boolean',
    ];

    // Override getAttribute to return translated content
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        // Translate these fields based on current locale
        if (in_array($key, ['title', 'description', 'button_text'])) {
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
    
    public function getDescriptionTranslations()
    {
        $value = $this->attributes['description'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
    
    public function getButtonTextTranslations()
    {
        $value = $this->attributes['button_text'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
}
