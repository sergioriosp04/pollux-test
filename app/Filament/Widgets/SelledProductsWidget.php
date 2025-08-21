<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SelledProductsWidget extends ChartWidget
{
    protected ?string $heading = 'Quantity selled products';

    protected function getData(): array
    {
        return $this->formatData($this->getProductsSelled());
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }

    private function getProductsSelled(): Collection
    {
        $productsSelled = DB::table('order_products', 'op')
            ->selectRaw('op.product_id, products.title, SUM(op.quantity) as total')
            ->join('products', 'op.product_id', '=', 'products.id')
            ->groupBy('products.id')
            ->get();

        return $productsSelled;
    }

    private function formatData(Collection $productsSelled): array
    {
        $colors = $this->generateColors($productsSelled->count());
        return [
            'datasets' => [
                [
                    'label' => 'Total selled',
                    'data' => $productsSelled->pluck('total')->toArray(),
                    'backgroundColor' => $colors['background'],
                ],
            ],
            'labels' => $productsSelled->pluck('title')->toArray(),
        ];
    }

    private function generateColors(int $count): array
    {
        $backgroundColors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 205, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)',
            'rgba(83, 102, 255, 0.8)',
            'rgba(255, 99, 255, 0.8)',
            'rgba(99, 255, 132, 0.8)',
        ];
        
        // Si hay más productos que colores, repetir los colores
        $backgroundSlice = array_slice($backgroundColors, 0, $count);
        
        if ($count > count($backgroundColors)) {
            $backgroundSlice = array_merge($backgroundSlice, array_slice($backgroundColors, 0, $count - count($backgroundColors)));
        }
        
        return [
            'background' => $backgroundSlice,
        ];
    }
}
