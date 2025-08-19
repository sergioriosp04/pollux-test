<?php

namespace App\Services\Export\Contracts;

interface ExportStrategyInterface
{
    public function export(array $data): string;
    public function getFileExtension(): string;
    public function getContentType(): string;
    public function getMimeType(): string;
}
