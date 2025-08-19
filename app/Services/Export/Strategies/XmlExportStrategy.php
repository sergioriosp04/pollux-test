<?php

namespace App\Services\Export\Strategies;

use App\Services\Export\Contracts\ExportStrategyInterface;

final class XmlExportStrategy implements ExportStrategyInterface
{
    public function export(array $data): string
    {
        // TODO: Implementation here
        return '';
    }

    public function getFileExtension(): string
    {
        return 'xml';
    }

    public function getContentType(): string
    {
        return 'text/xml';
    }

    public function getMimeType(): string
    {
        return 'text/xml';
    }
}