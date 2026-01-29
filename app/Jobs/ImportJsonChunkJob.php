<?php

namespace App\Jobs;

use App\Services\ProductJsonImporter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportJsonChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public int $tries = 3;

    public function __construct(
        public string $chunkFilePath
    ) {}

    public function handle(ProductJsonImporter $importer): void
    {
        try {
            if (!is_readable($this->chunkFilePath)) {
                Log::warning('ImportJsonChunkJob: Chunk file not readable', ['path' => $this->chunkFilePath]);
                return;
            }

            // NDJSON streaming: one JSON object per line (memory safe with base64 fields)
            $fh = fopen($this->chunkFilePath, 'rb');
            if ($fh === false) {
                Log::warning('ImportJsonChunkJob: Cannot open chunk file', ['path' => $this->chunkFilePath]);
                return;
            }

            $buffer = [];
            $created = 0;
            $updated = 0;
            $skipped = 0;

            while (($line = fgets($fh)) !== false) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }
                $item = json_decode($line, true);
                if (!is_array($item)) {
                    $skipped++;
                    continue;
                }
                $buffer[] = $item;

                if (count($buffer) >= 10) {
                    $r = $importer->processChunkFromItemArrays($buffer);
                    $created += $r['created'];
                    $updated += $r['updated'];
                    $skipped += $r['skipped'];
                    $buffer = [];
                }
            }

            fclose($fh);

            if ($buffer !== []) {
                $r = $importer->processChunkFromItemArrays($buffer);
                $created += $r['created'];
                $updated += $r['updated'];
                $skipped += $r['skipped'];
            }

            Log::info('ImportJsonChunkJob: Processed chunk', [
                'path' => $this->chunkFilePath,
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
            ]);
        } catch (\Throwable $e) {
            Log::error('ImportJsonChunkJob failed', [
                'path' => $this->chunkFilePath,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        } finally {
            $this->deleteChunkFile();
        }
    }

    private function deleteChunkFile(): void
    {
        if (is_file($this->chunkFilePath)) {
            @unlink($this->chunkFilePath);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ImportJsonChunkJob permanently failed', [
            'path' => $this->chunkFilePath,
            'message' => $exception->getMessage(),
        ]);
        $this->deleteChunkFile();
    }
}
