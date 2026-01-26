<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Make description nullable
            $table->json('description')->nullable()->change();
            
            // Make button_text nullable
            $table->json('button_text')->nullable()->change();
            
            // Make image nullable
            $table->longText('image')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->json('description')->nullable(false)->change();
            $table->json('button_text')->nullable(false)->change();
            $table->longText('image')->nullable(false)->change();
        });
    }
};
