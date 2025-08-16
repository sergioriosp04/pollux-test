<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $order = $this->record;
        foreach ($order->orderProducts as $orderProduct) {
            if ($orderProduct->wasChanged('quantity')) {
                $product = $orderProduct->product;
                $product->stock += $orderProduct->getPrevious()['quantity'] - $orderProduct->quantity;
                $product->save();
            }
        }
        return $data;
    }
}
