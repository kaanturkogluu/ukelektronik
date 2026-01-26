<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $services = DB::table('services')->get()->toArray();
        
        Schema::table('services', function (Blueprint $table) {
            $table->json('title_json')->nullable()->after('title');
            $table->json('short_description_json')->nullable()->after('short_description');
            $table->json('description_json')->nullable()->after('description');
        });
        
        foreach ($services as $service) {
            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'title_json' => json_encode(['tr' => $service->title ?? '', 'en' => $service->title ?? '']),
                    'short_description_json' => json_encode(['tr' => $service->short_description ?? '', 'en' => $service->short_description ?? '']),
                    'description_json' => json_encode(['tr' => $service->description ?? '', 'en' => $service->description ?? '']),
                ]);
        }
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'short_description', 'description']);
        });
        
        Schema::table('services', function (Blueprint $table) {
            $table->json('title')->after('slug');
            $table->json('short_description')->after('title');
            $table->json('description')->after('short_description');
        });
        
        $servicesWithJson = DB::table('services')->get();
        foreach ($servicesWithJson as $service) {
            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'title' => $service->title_json,
                    'short_description' => $service->short_description_json,
                    'description' => $service->description_json,
                ]);
        }
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title_json', 'short_description_json', 'description_json']);
        });
    }

    public function down(): void
    {
        $services = DB::table('services')->get()->toArray();
        
        Schema::table('services', function (Blueprint $table) {
            $table->string('title_temp')->after('slug');
            $table->text('short_description_temp')->after('title_temp');
            $table->longText('description_temp')->after('short_description_temp');
        });
        
        foreach ($services as $service) {
            $title = is_string($service->title) ? json_decode($service->title, true) : ($service->title ?? []);
            $shortDescription = is_string($service->short_description) ? json_decode($service->short_description, true) : ($service->short_description ?? []);
            $description = is_string($service->description) ? json_decode($service->description, true) : ($service->description ?? []);
            
            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'title_temp' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'short_description_temp' => is_array($shortDescription) ? ($shortDescription['tr'] ?? '') : '',
                    'description_temp' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title', 'short_description', 'description']);
            $table->string('title')->after('slug');
            $table->text('short_description')->after('title');
            $table->longText('description')->after('short_description');
        });
        
        foreach ($services as $service) {
            $title = is_string($service->title) ? json_decode($service->title, true) : ($service->title ?? []);
            $shortDescription = is_string($service->short_description) ? json_decode($service->short_description, true) : ($service->short_description ?? []);
            $description = is_string($service->description) ? json_decode($service->description, true) : ($service->description ?? []);
            
            DB::table('services')
                ->where('id', $service->id)
                ->update([
                    'title' => is_array($title) ? ($title['tr'] ?? '') : '',
                    'short_description' => is_array($shortDescription) ? ($shortDescription['tr'] ?? '') : '',
                    'description' => is_array($description) ? ($description['tr'] ?? '') : '',
                ]);
        }
        
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title_temp', 'short_description_temp', 'description_temp']);
        });
    }
};
