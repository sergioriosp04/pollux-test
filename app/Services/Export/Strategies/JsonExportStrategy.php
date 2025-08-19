<?php

namespace App\Services\Export\Strategies;

use App\Services\Export\Contracts\ExportStrategyInterface;

final class JsonExportStrategy implements ExportStrategyInterface
{
    public function export(array $data): string
    {
        return json_encode([
            'data' => $data,
            'meta' => [
                'total' => count($data),
                'exported_at' => now()->toISOString(),
                'format' => 'json'
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function getFileExtension(): string
    {
        return 'json';
    }

    public function getContentType(): string
    {
        return 'application/json';
    }

    public function getMimeType(): string
    {
        return 'application/json';
    }
}