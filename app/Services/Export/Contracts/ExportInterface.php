<?php

namespace App\Services\Export\Contracts;

use App\Services\Export\Contracts\ExportStrategyInterface;

interface ExportInterface
{
    public function setStrategy(ExportStrategyInterface $strategy): void;
    public function executeExport(array $data): array;
}