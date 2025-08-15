<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Order;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $totalOrder = 0;
        if (!empty($data['orderProducts'])) {
            $totalOrder = collect($data['orderProducts'])->map(function ($item) {
                return $item['total'];
            })->sum();
        }

        $data['total'] = $totalOrder;
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;

        if ($order->orderProducts->isNotEmpty()) {
            foreach ($order->orderProducts as $orderProduct) {
                $quantity = $orderProduct->quantity;
                $product = $orderProduct->product;
                $product->stock -= $quantity;
                $product->save();
            }
        }
    }
}
