<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update services table
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'image')) {
            Schema::table('services', function (Blueprint $table) {
                $table->longText('image')->nullable()->change();
            });
        }

        // Update projects table
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'image')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->longText('image')->nullable()->change();
            });
        }

        // Update blogs table
        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'image')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->longText('image')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        // Revert services table
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'image')) {
            Schema::table('services', function (Blueprint $table) {
                $table->string('image')->nullable()->change();
            });
        }

        // Revert projects table
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'image')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('image')->nullable()->change();
            });
        }

        // Revert blogs table
        if (Schema::hasTable('blogs') && Schema::hasColumn('blogs', 'image')) {
            Schema::table('blogs', function (Blueprint $table) {
                $table->string('image')->nullable()->change();
            });
        }
    }
};
