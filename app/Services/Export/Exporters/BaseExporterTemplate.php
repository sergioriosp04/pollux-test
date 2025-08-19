<?php

namespace App\Services\Export\Exporters;

use App\Services\Export\Strategies\ContextExporter;
use App\Services\Export\Strategies\CsvExportStrategy;
use App\Services\Export\Strategies\JsonExportStrategy;
use App\Services\Export\Strategies\XmlExportStrategy;
use InvalidArgumentException;

abstract class BaseExporterTemplate
{
    private ContextExporter $exportContext;
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->exportContext = new ContextExporter(new JsonExportStrategy());
        $this->filters = $filters;
    }
    
    final public function exportWithFormat(string $format): array
    {
        $strategy = match($format) {
            'csv' => new CsvExportStrategy(),
            'json' => new JsonExportStrategy(),
            'xml' => new XmlExportStrategy(),
            default => throw new InvalidArgumentException("Format {$format} not supported")
        };
        
        $this->exportContext->setStrategy($strategy);
        return $this->performExport();
    }
    
    final private function performExport(): array
    {
        $data = $this->getData();
        $result = $this->exportContext->executeExport($data);
        
        $result['filename'] = $this->generateFileName($result['extension']);
        $result['recordsCount'] = count($data);
        
        return $result;
    }
    
    protected function generateFileName(string $extension): string
    {
        return $this->getFileName() . now()->format('Y_m_d_H_i_s') . '.' . $extension;
    }

    abstract protected function getData(): array;
    abstract protected function getFileName(): string;
}