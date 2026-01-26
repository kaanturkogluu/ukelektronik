<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $projects = DB::table('projects')->get()->toArray();
        
        Schema::table('projects', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
            $table->json('description_json')->nullable()->after('description');
        });
        
        foreach ($projects as $project) {
            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'title_json' => json_encode(['tr' => $project->title ?? '', 'en' => $project->title ?? '']),
                    'description_json' => json_encode(['tr' => $project->description ?? '', 'en' => $project->description ?? '']),
                ]);
        }
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->json('title')->after('slug');
            $table->json('description')->after('title');
        });
        
        $projectsWithJson = DB::table('projects')->get();
        foreach ($projectsWithJson as $project) {
            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'title' => $project->title_json,
                    'description' => $project->description_json,
                ]);
        }
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title_json', 'description_json']);
        });
    }

    public function down(): void
    {
        $projects = DB::table('projects')->get()->toArray();
        
        Schema::table('projects', function (Blueprint $table) {
            $table->string('title_temp')->after('slug');
            $table->longText('description_temp')->after('title_temp');
        });
        
        foreach ($projects as $project) {
            $title = is_string($project->title) ? json_decode($project->title, true) : ($project->title ?? []);
            $description = is_string($project->description) ? json_decode($project->description, true) : ($project->description ?? []);
            
            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'title_temp' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'description_temp' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
            $table->string('title')->after('slug');
            $table->longText('description')->after('title');
        });
        
        foreach ($projects as $project) {
            $title = is_string($project->title) ? json_decode($project->title, true) : ($project->title ?? []);
            $description = is_string($project->description) ? json_decode($project->description, true) : ($project->description ?? []);
            
            DB::table('projects')
                ->where('id', $project->id)
                ->update([
                    'title' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'description' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title_temp', 'description_temp']);
        });
    }
};
