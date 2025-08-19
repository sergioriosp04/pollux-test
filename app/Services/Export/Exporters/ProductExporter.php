<?php

namespace App\Services\Export\Exporters;

use App\Models\Product;

class ProductExporter extends BaseExporterTemplate
{
    protected function getData(): array
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
                'currency' => $product->currency ? $product->currency->code : 'USD',
            ];
        })->toArray();
    }

    protected function getFileName(): string
    {
        return 'products_';
    }
}