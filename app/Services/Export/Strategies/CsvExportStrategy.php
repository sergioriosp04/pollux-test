<?php

namespace App\Services\Export\Strategies;

use App\Services\Export\Contracts\ExportStrategyInterface;

final class CsvExportStrategy implements ExportStrategyInterface
{
    public function export(array $data): string
    {
        // TODO: Implementation here
        return '';
    }

    public function getFileExtension(): string
    {
        return 'csv';
    }

    public function getContentType(): string
    {
        return 'text/csv';
    }

    public function getMimeType(): string
    {
        return 'text/csv';
    }
}