<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'question' => 'array',
        'answer' => 'array',
        'is_active' => 'boolean',
    ];
    
    // Override getAttribute to return translated content
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        // Translate these fields based on current locale
        if (in_array($key, ['question', 'answer'])) {
            $locale = app()->getLocale();
            $translations = is_string($value) ? json_decode($value, true) : ($value ?? []);
            
            if (is_array($translations)) {
                return $translations[$locale] ?? $translations['tr'] ?? $translations['en'] ?? '';
            }
        }
        
        return $value;
    }
    
    // Get raw translations for admin forms
    public function getQuestionTranslations()
    {
        $value = $this->attributes['question'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
    
    public function getAnswerTranslations()
    {
        $value = $this->attributes['answer'] ?? null;
        return is_string($value) ? json_decode($value, true) : ($value ?? ['tr' => '', 'en' => '']);
    }
}
