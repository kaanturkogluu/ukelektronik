<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JsonMachine\Items;
use JsonMachine\JsonDecoder\ExtJsonDecoder;

class ImportJsonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Max item count per chunk file.
     */
    public const CHUNK_SIZE = 50;

    /**
     * Max bytes per chunk file (NDJSON). Prevents large base64 fields from
     * accumulating too much in memory before being flushed to disk.
     */
    public const MAX_CHUNK_BYTES = 2_000_000; // ~2MB

    public int $timeout = 1800;

    public int $tries = 2;

    public function __construct(
        public string $filePath
    ) {}

    public function handle(): void
    {
        if (!is_readable($this->filePath)) {
            Log::error('ImportJsonJob: JSON file not readable', ['path' => $this->filePath]);
            throw new \RuntimeException('JSON dosyası okunamadı: ' . $this->filePath);
        }

        $storageDir = storage_path('app/import-chunks');
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }

        $runId = Str::uuid()->toString();
        $decoder = new ExtJsonDecoder(true); // decode objects as associative arrays

        try {
            $itemsIterator = $this->makeItemsIterator($decoder);

            $index = 0;
            $chunkCount = 0;

            $fh = null;
            $path = null;
            $countInFile = 0;
            $bytesInFile = 0;

            $openNew = function () use ($storageDir, $runId, &$index, &$fh, &$path, &$countInFile, &$bytesInFile) {
                $path = $storageDir . '/' . $runId . '_' . $index . '.ndjson';
                $fh = fopen($path, 'wb');
                if ($fh === false) {
                    throw new \RuntimeException('Chunk dosyası yazılamadı: ' . $path);
                }
                $countInFile = 0;
                $bytesInFile = 0;
            };

            $flushAndDispatch = function () use (&$fh, &$path, &$countInFile, &$bytesInFile, &$chunkCount, &$index) {
                if ($fh !== null) {
                    fclose($fh);
                    $fh = null;
                }
                if (!empty($path) && $countInFile > 0) {
                    ImportJsonChunkJob::dispatch($path);
                    $chunkCount++;
                    $index++;
                } elseif (!empty($path) && is_file($path)) {
                    @unlink($path);
                }
                $path = null;
                $countInFile = 0;
                $bytesInFile = 0;
            };

            $openNew();

            foreach ($itemsIterator as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $line = json_encode($item, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($line === false) {
                    continue;
                }
                $line .= "\n";
                $lineBytes = strlen($line);

                // If adding this item would exceed limits, flush current file first
                if ($countInFile > 0 && ($countInFile >= self::CHUNK_SIZE || ($bytesInFile + $lineBytes) > self::MAX_CHUNK_BYTES)) {
                    $flushAndDispatch();
                    $openNew();
                }

                // Write line
                fwrite($fh, $line);
                $countInFile++;
                $bytesInFile += $lineBytes;
            }

            // flush last
            $flushAndDispatch();

            Log::info('ImportJsonJob: Dispatched chunk jobs', [
                'file' => $this->filePath,
                'chunk_count' => $chunkCount,
                'chunk_size' => self::CHUNK_SIZE,
                'max_chunk_bytes' => self::MAX_CHUNK_BYTES,
            ]);
        } catch (\Throwable $e) {
            Log::error('ImportJsonJob failed', [
                'file' => $this->filePath,
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('ImportJsonJob permanently failed', [
            'file' => $this->filePath,
            'message' => $exception->getMessage(),
        ]);
    }

    private function makeItemsIterator(ExtJsonDecoder $decoder): iterable
    {
        $firstChar = $this->firstNonWhitespaceChar($this->filePath);

        // Common case: the JSON is a single top-level array: [ {..}, {..}, ... ]
        if ($firstChar === '[') {
            return Items::fromFile($this->filePath, ['decoder' => $decoder]);
        }

        // Wrapper object case: { "items": [ ... ] } or { "products": [ ... ] } or { "data": [ ... ] }
        foreach (['/items', '/products', '/data'] as $pointer) {
            $it = Items::fromFile($this->filePath, ['pointer' => $pointer, 'decoder' => $decoder]);

            // Peek one item without materializing everything
            foreach ($it as $first) {
                // Recreate iterator because we consumed the first element
                return Items::fromFile($this->filePath, ['pointer' => $pointer, 'decoder' => $decoder]);
            }
        }

        throw new \RuntimeException('JSON formatı tanınamadı. Beklenen: [ {...} ] veya {\"items\": [..]} / {\"products\": [..]} / {\"data\": [..]}');
    }

    private function firstNonWhitespaceChar(string $filePath): ?string
    {
        $fh = fopen($filePath, 'rb');
        if ($fh === false) {
            return null;
        }
        try {
            $buf = fread($fh, 4096);
            if ($buf === false) {
                return null;
            }
            $len = strlen($buf);
            for ($i = 0; $i < $len; $i++) {
                $c = $buf[$i];
                if (!ctype_space($c)) {
                    return $c;
                }
            }
            return null;
        } finally {
            fclose($fh);
        }
    }
}
