<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $totalOrder = 0;
        if (!empty($data['orderProducts'])) {
            $totalOrder = collect($data['orderProducts'])->map(function ($item) use ($totalOrder) {
                return $item['total'];
            })->sum();
        }

        $data['total'] = $totalOrder;
        return $data;
    }
}
