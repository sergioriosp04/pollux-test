<?php

namespace App\Services\Export\Exporters;

use App\Models\Order;

class OrderExporter extends BaseExporterTemplate
{
    protected function getData(): array
    {
        $query = Order::query()->with(['status', 'orderProducts']);

        // TODO: filters logic
        // foreach ($this->filters as $key => $value) {
        // }

        return $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'title' => $order->title,
                'total' => $order->total,
                'status' => $order->status->title,
                'order' => empty($order->orderProducts) ? [] : $order->orderProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'unit_price' => $product->unit_price,
                        'quantity' => $product->quantity,
                        'total' => $product->total,
                    ];
                }),
            ];
        })->toArray();
    }

    protected function getFileName(): string
    {
        return 'orders_';
    }
}