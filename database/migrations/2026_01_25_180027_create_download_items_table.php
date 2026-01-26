<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['brand', 'category', 'file'])->default('category');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('file_path')->nullable(); // Sadece file type için
            $table->string('file_type')->nullable(); // excel, word, pdf
            $table->string('original_filename')->nullable(); // Orijinal dosya adı
            $table->integer('file_size')->nullable(); // Dosya boyutu (bytes)
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('download_items')->onDelete('cascade');
            $table->index('parent_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('download_items');
    }
};
