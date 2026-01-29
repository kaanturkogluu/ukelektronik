<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_code',
        'slug',
        'name',
        'category_id',
        'brand_id',
        'stock_amount',
        'image',
        'images',
        'description',
        'specs',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'specs' => 'array',
        'features' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}

