<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'category_id',
        'image',
        'description',
        'details',
        'features',
        'gallery',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'details' => 'array',
        'features' => 'array',
        'gallery' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }
    
    // Override getAttribute to return translated content (uses same decode as admin form)
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, ['title', 'description'])) {
            $translations = $this->decodeTranslationField(is_string($value) ? $value : (is_array($value) ? $value : null));
            $locale = app()->getLocale();
            return $translations[$locale] ?? $translations['tr'] ?? $translations['en'] ?? '';
        }
        
        return $value;
    }
    
    /**
     * Decode JSON translation string/array. Handles:
     * - "{\"tr\":\"...\",\"en\":\"...\"}" (escaped JSON string, single or double encoded)
     * - Already decoded array from cast/DB
     * - Unicode escapes like \u0131 (ı) are decoded by json_decode
     */
    protected function decodeTranslationField($value): array
    {
        if ($value === null) {
            return ['tr' => '', 'en' => ''];
        }
        if (is_array($value)) {
            return [
                'tr' => $value['tr'] ?? '',
                'en' => $value['en'] ?? '',
            ];
        }
        if (!is_string($value)) {
            return ['tr' => '', 'en' => ''];
        }
        $decoded = json_decode($value, true);
        // Double-encoded: first decode gave a string, decode again
        if (is_string($decoded)) {
            $decoded = json_decode($decoded, true);
        }
        if (!is_array($decoded)) {
            return ['tr' => '', 'en' => ''];
        }
        return [
            'tr' => $decoded['tr'] ?? '',
            'en' => $decoded['en'] ?? '',
        ];
    }

    public function getTitleTranslations(): array
    {
        $value = $this->attributes['title'] ?? null;
        return $this->decodeTranslationField($value);
    }

    public function getDescriptionTranslations(): array
    {
        $value = $this->attributes['description'] ?? null;
        return $this->decodeTranslationField($value);
    }
}
