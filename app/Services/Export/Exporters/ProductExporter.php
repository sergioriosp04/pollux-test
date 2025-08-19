<?php

namespace App\Services\Export\Exporters;

use App\Models\Product;
use App\Services\Export\Strategies\CsvExportStrategy;
use App\Services\Export\Strategies\JsonExportStrategy;
use App\Services\Export\Strategies\XmlExportStrategy;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

class ProductExporter extends ContextExporter
{
    private ContextExporter $exportContext;
    private array $filters;

    public function __construct(array $filters = [])
    {
        $this->exportContext = new ContextExporter(new JsonExportStrategy());
        $this->filters = $filters;
    }
    
    public function exportWithFormat(string $format): array
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
    
    private function performExport(): array
    {
        $data = $this->getData();
        $result = $this->exportContext->executeExport($data);
        
        $result['filename'] = $this->generateFileName($result['extension']);
        $result['recordsCount'] = count($data);
        
        return $result;
    }
    
    private function getData(): array
    {
        $query = Product::query();

        // TODO: filters logic
        // foreach ($this->filters as $key => $value) {
        // }
        
        return $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'stock' => $product->stock ?? 0,
            ];
        })->toArray();
    }
    
    private function generateFileName(string $extension): string
    {
        return $this->getFileName() . now()->format('Y_m_d_H_i_s') . '.' . $extension;
    }

    private function getFileName(): string
    {
        return 'products_';
    }
}