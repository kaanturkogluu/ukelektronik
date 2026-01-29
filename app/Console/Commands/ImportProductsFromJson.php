<?php

namespace App\Console\Commands;

use App\Jobs\ImportJsonJob;
use Illuminate\Console\Command;

class ImportProductsFromJson extends Command
{
    protected $signature = 'products:import-json {file=urunler.json}';

    protected $description = 'Import products from JSON file (urunler.json) via queue; chunk size 50';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("JSON file not found: {$filePath}");
            return 1;
        }

        $this->info("Dispatching ImportJsonJob for: {$filePath}");

        try {
            ImportJsonJob::dispatch($filePath);
            $this->info('ImportJsonJob dispatched. Chunk jobs (50 items each) will be queued.');
            $this->info('Run: php artisan queue:work');
        } catch (\Throwable $e) {
            $this->error('Dispatch failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
