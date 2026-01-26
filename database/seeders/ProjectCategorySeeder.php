<?php

namespace Database\Seeders;

use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Güneş Enerjisi',
                'slug' => 'gunes-enerjisi',
                'description' => 'Güneş enerjisi projeleri',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Rüzgar Enerjisi',
                'slug' => 'ruzgar-enerjisi',
                'description' => 'Rüzgar enerjisi projeleri',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Hidroelektrik',
                'slug' => 'hidroelektrik',
                'description' => 'Hidroelektrik enerji projeleri',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Elektrik Dağıtım',
                'slug' => 'elektrik-dagitim',
                'description' => 'Elektrik dağıtım projeleri',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Elektrik İletim',
                'slug' => 'elektrik-iletim',
                'description' => 'Elektrik iletim projeleri',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $category) {
            ProjectCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
