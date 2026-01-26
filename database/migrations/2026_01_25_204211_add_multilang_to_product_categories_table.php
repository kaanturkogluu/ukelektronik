<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = DB::table('product_categories')->get()->toArray();
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->json('name_json')->nullable()->after('name');
            $table->json('description_json')->nullable()->after('description');
        });
        
        foreach ($categories as $category) {
            DB::table('product_categories')
                ->where('id', $category->id)
                ->update([
                    'name_json' => json_encode(['tr' => $category->name ?? '', 'en' => $category->name ?? '']),
                    'description_json' => json_encode(['tr' => $category->description ?? '', 'en' => $category->description ?? '']),
                ]);
        }
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
        });
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->json('name')->after('id');
            $table->json('description')->nullable()->after('name');
        });
        
        $categoriesWithJson = DB::table('product_categories')->get();
        foreach ($categoriesWithJson as $category) {
            DB::table('product_categories')
                ->where('id', $category->id)
                ->update([
                    'name' => $category->name_json,
                    'description' => $category->description_json,
                ]);
        }
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['name_json', 'description_json']);
        });
    }

    public function down(): void
    {
        $categories = DB::table('product_categories')->get()->toArray();
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('name_temp')->after('id');
            $table->text('description_temp')->nullable()->after('name_temp');
        });
        
        foreach ($categories as $category) {
            $name = is_string($category->name) ? json_decode($category->name, true) : ($category->name ?? []);
            $description = is_string($category->description) ? json_decode($category->description, true) : ($category->description ?? []);
            
            DB::table('product_categories')
                ->where('id', $category->id)
                ->update([
                    'name_temp' => is_array($name) ? ($name['tr'] ?? '') : '',
                    'description_temp' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'description']);
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
        });
        
        foreach ($categories as $category) {
            $name = is_string($category->name) ? json_decode($category->name, true) : ($category->name ?? []);
            $description = is_string($category->description) ? json_decode($category->description, true) : ($category->description ?? []);
            
            DB::table('product_categories')
                ->where('id', $category->id)
                ->update([
                    'name' => is_array($name) ? ($name['tr'] ?? '') : '',
                    'description' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['name_temp', 'description_temp']);
        });
    }
};
