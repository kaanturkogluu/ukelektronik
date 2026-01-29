<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_code')) {
                $table->string('stock_code')->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('products', 'brand_id')) {
                $table->foreignId('brand_id')->nullable()->after('category_id')->constrained('brands')->onDelete('set null');
            }
            if (!Schema::hasColumn('products', 'stock_amount')) {
                $table->unsignedInteger('stock_amount')->default(0)->after('brand_id');
            }
            if (!Schema::hasColumn('products', 'images')) {
                $table->json('images')->nullable()->after('image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }
            if (Schema::hasColumn('products', 'stock_amount')) {
                $table->dropColumn('stock_amount');
            }
            if (Schema::hasColumn('products', 'brand_id')) {
                $table->dropForeign(['brand_id']);
                $table->dropColumn('brand_id');
            }
            if (Schema::hasColumn('products', 'stock_code')) {
                $table->dropUnique(['stock_code']);
                $table->dropColumn('stock_code');
            }
        });
    }
};
