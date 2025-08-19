<?php

namespace App\Services\Export\Exporters;

use App\Services\Export\Contracts\ExportInterface;
use App\Services\Export\Contracts\ExportStrategyInterface;

class ContextExporter implements ExportInterface
{
    private ExportStrategyInterface $strategy;
    
    public function __construct(ExportStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    
    public function setStrategy(ExportStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }
    
    public function executeExport(array $data): array
    {
        return [
            'content' => $this->strategy->export($data),
            'extension' => $this->strategy->getFileExtension(),
            'contentType' => $this->strategy->getContentType(),
            'mimeType' => $this->strategy->getMimeType(),
        ];
    }
}