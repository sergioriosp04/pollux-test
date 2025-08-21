<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->columns(2)
                    ->required(),
                Select::make('status_id')
                    ->relationship(
                        'status',
                        'title',
                        fn ($query) => $query
                            ->whereNot('id', OrderStatus::CANCELED)
                        )
                    ->columns(2)
                    ->required(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),

                Hidden::make('id'),

                Repeater::make('orderProducts')
                    ->columnSpanFull()
                    ->dehydrated() // Add fields to the request
                    ->columns(2)
                    ->defaultItems(1)
                    ->relationship('orderProducts')
                    ->addActionLabel('Add product')
                    ->schema([
                        Select::make('product_id')
                        ->required()
                        ->searchable()
                        ->live()
                        ->columnSpan(['default' => 2, 'md' => 1])
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                        ->relationship(
                            'product', 
                            'title',
                            fn ($query) => $query
                                ->where('status_id', ProductStatus::ACTIVE)
                            )
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            self::getProductInfo($set, $get, $state);
                        })
                        // for update action
                        ->afterStateHydrated(function (Set $set, Get $get, $state) {
                            self::getProductInfo($set, $get, $state);
                        }),

                        TextInput::make('title')
                            ->required()
                            ->columnSpan(['default' => 2, 'md' => 1]),
                        
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->live()
                            ->columnSpan(['default' => 2, 'md' => 1])
                            ->maxValue(function (Get $get) {
                                $stock = $get('stock');
                                $isEditing = $get('id');
                                if ($stock !== null) {
                                    if ($isEditing) {
                                        return $stock + $get('original_quantity');
                                    }
                                    return $stock;
                                }
                                return 0;
                            })
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                self::setTotalsAccordingToQuantity($set, $get, $state);
                            })
                            // for update action
                            ->afterStateHydrated(function (Set $set, Get $get, $state) {
                                self::setTotalsAccordingToQuantity($set, $get, $state);
                                $set('original_quantity', $get('quantity'));
                            })
                            ->helperText(function (Get $get) {
                                $stock = $get('stock');
                                if ($stock !== null) {
                                    return "Stock : {$stock}";
                                }
                                return 'First you should add a product';
                            }),

                        TextInput::make('total_price_display')
                            ->disabled()
                            ->label('Total Price')
                            ->prefix('$')
                            ->columnSpan(['default' => 2, 'md' => 1]),

                        // it won´t add to the insert if it is not occulted
                        Hidden::make('total'),
                        Hidden::make('unit_price'),
                    ])
            ]);
    }

    private static function getProductInfo(Set $set, Get $get, $productId) {
        if ($productId) {
            $product = \App\Models\Product::find($productId);

            if ($product) {
                $set('unit_price', $product->price);
                $set('total', $product->price);
                $set('total_price_display', number_format($product->price, 2));
                $set('title', $product->title);
                $set('stock', $product->stock);
                $set('currency', $product->currency);
            }
        }
    }

    private static function setTotalsAccordingToQuantity(Set $set, Get $get, $quantity) {
        $stock = $get('stock');
        if ($quantity && $stock) {
            $totalPrice = round((float) $quantity * $get('unit_price'), 2);
            $set('total', $totalPrice);
            $set('total_price_display', number_format($totalPrice, 2));
        }
    }
}
