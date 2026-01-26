<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Güneş Panelleri',
                'slug' => 'gunes-panelleri',
                'description' => 'Güneş enerjisi panelleri',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'İnverterler',
                'slug' => 'inverterler',
                'description' => 'Güneş enerjisi inverterleri',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Batarya Sistemleri',
                'slug' => 'batarya-sistemleri',
                'description' => 'Enerji depolama bataryaları',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Montaj Sistemleri',
                'slug' => 'montaj-sistemleri',
                'description' => 'Güneş paneli montaj ekipmanları',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Kablo ve Bağlantı Elemanları',
                'slug' => 'kablo-ve-baglanti-elemanlari',
                'description' => 'Elektrik kabloları ve bağlantı elemanları',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
