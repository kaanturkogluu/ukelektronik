<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mevcut verileri yedekle
        $blogs = DB::table('blogs')->get()->toArray();
        
        // Yeni JSON kolonlar ekle
        Schema::table('blogs', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
            $table->json('excerpt_json')->nullable()->after('excerpt');
            $table->json('content_json')->nullable()->after('content');
        });
        
        // Mevcut verileri JSON formatına çevir
        foreach ($blogs as $blog) {
            DB::table('blogs')
                ->where('id', $blog->id)
                ->update([
                    'title_json' => json_encode(['tr' => $blog->title ?? '', 'en' => $blog->title ?? '']),
                    'excerpt_json' => json_encode(['tr' => $blog->excerpt ?? '', 'en' => $blog->excerpt ?? '']),
                    'content_json' => json_encode(['tr' => $blog->content ?? '', 'en' => $blog->content ?? '']),
                ]);
        }
        
        // Eski kolonları sil
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'content']);
        });
        
        // JSON kolonları ekle
        Schema::table('blogs', function (Blueprint $table) {
            $table->json('title')->after('slug');
            $table->json('excerpt')->after('title');
            $table->json('content')->after('excerpt');
        });
        
        // Verileri geri kopyala (geçici kolonlardan)
        $blogsWithJson = DB::table('blogs')->get();
        foreach ($blogsWithJson as $blog) {
            DB::table('blogs')
                ->where('id', $blog->id)
                ->update([
                    'title' => $blog->title_json,
                    'excerpt' => $blog->excerpt_json,
                    'content' => $blog->content_json,
                ]);
        }
        
        // Geçici kolonları sil
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['title_json', 'excerpt_json', 'content_json']);
        });
    }

    public function down(): void
    {
        $blogs = DB::table('blogs')->get()->toArray();
        
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title_temp')->after('slug');
            $table->text('excerpt_temp')->after('title_temp');
            $table->longText('content_temp')->after('excerpt_temp');
        });
        
        foreach ($blogs as $blog) {
            $title = is_string($blog->title) ? json_decode($blog->title, true) : ($blog->title ?? []);
            $excerpt = is_string($blog->excerpt) ? json_decode($blog->excerpt, true) : ($blog->excerpt ?? []);
            $content = is_string($blog->content) ? json_decode($blog->content, true) : ($blog->content ?? []);
            
            DB::table('blogs')
                ->where('id', $blog->id)
                ->update([
                    'title_temp' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'excerpt_temp' => is_array($excerpt) ? ($excerpt['tr'] ?? '') : '',
                    'content_temp' => is_array($content) ? ($content['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['title', 'excerpt', 'content']);
        });
        
        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title')->after('slug');
            $table->text('excerpt')->after('title');
            $table->longText('content')->after('excerpt');
        });
        
        foreach ($blogs as $blog) {
            $title = is_string($blog->title) ? json_decode($blog->title, true) : ($blog->title ?? []);
            $excerpt = is_string($blog->excerpt) ? json_decode($blog->excerpt, true) : ($blog->excerpt ?? []);
            $content = is_string($blog->content) ? json_decode($blog->content, true) : ($blog->content ?? []);
            
            DB::table('blogs')
                ->where('id', $blog->id)
                ->update([
                    'title' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'excerpt' => is_array($excerpt) ? ($excerpt['tr'] ?? '') : '',
                    'content' => is_array($content) ? ($content['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['title_temp', 'excerpt_temp', 'content_temp']);
        });
    }
};
