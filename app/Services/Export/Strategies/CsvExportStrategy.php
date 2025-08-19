<?php

namespace App\Services\Export\Strategies;

use App\Services\Export\Contracts\ExportStrategyInterface;

final class CsvExportStrategy implements ExportStrategyInterface
{
    public function export(array $data): string
    {
        $output = fopen('php://temp', 'r+');
        fputcsv($output, array_keys($data[0]));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
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