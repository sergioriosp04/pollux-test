<?php

namespace App\Services\Export\Strategies;

use App\Services\Export\Contracts\ExportStrategyInterface;

final class XlsxExportStrategy implements ExportStrategyInterface
{
    public function export(array $data): string
    {
        // TODO: Implementation here
        return '';
    }

    public function getFileExtension(): string
    {
        return 'xlsx';
    }

    public function getContentType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }

    public function getMimeType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
}