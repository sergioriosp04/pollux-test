<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Common\ProductStatesCommon;
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
                    ->relationship('status', 'title')
                    ->columns(2)
                    ->required(),
                Textarea::make('body')
                    ->required()
                    ->columnSpanFull(),

                Repeater::make('orderProducts')
                    ->columnSpanFull()
                    ->relationship('orderProducts')
                    ->schema([
                        Select::make('product_id')
                        ->relationship(
                            name: 'product', 
                            titleAttribute: 'title',
                            modifyQueryUsing: fn ($query) => $query
                                ->where('stock', '>', 0)
                                ->where('status_id', ProductStatesCommon::ACTIVE)
                            )
                        ->required()
                        ->searchable()
                        ->live()
                        ->columnSpan(['default' => 2, 'md' => 1])
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            if ($state) {
                                $product = \App\Models\Product::find($state);
                                
                                if ($product) {
                                    $set('price', $product->price);
                                    $set('unit_price', $product->price);
                                    $set('total', $product->price);
                                    $set('total_price_display', number_format($product->price, 2));
                                    $set('title', $product->title);
                                    $set('stock', $product->stock);
                                    $set('currency', $product->currency);
                                }
                            }
                        })
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        TextInput::make('title')
                            ->dehydrated()
                            ->columnSpan(['default' => 2, 'md' => 1])
                            ->required(),
                        
                        TextInput::make('quantity')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->maxValue(function (Get $get) {
                                $stock = $get('stock');
                                if ($stock) {
                                    return $stock;
                                }
                                return 0;
                            })
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                $stock = $get('stock');
                                if ($state && $stock) {
                                    $totalPrice = round((float) $state * $get('price'), 2);
                                    $set('total', $totalPrice);
                                    $set('total_price_display', number_format($totalPrice, 2));
                                }
                            })
                            ->columnSpan(['default' => 2, 'md' => 1])
                            ->helperText(function (Get $get) {
                                $stock = $get('stock');
                                if ($stock) {
                                    return "Stock : {$stock}";
                                }
                                return 'First you should add a product';
                            }),

                        TextInput::make('total_price_display')
                            ->label('Total Price')
                            ->prefix('$')
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) : '')
                            ->columnSpan(['default' => 2, 'md' => 1])
                            ->disabled(),

                        Hidden::make('total'),
                        Hidden::make('price'),
                        Hidden::make('unit_price'),
                        Hidden::make('stock'),
                        Hidden::make('currency'),
                    ])
                    ->columns(2)
                    ->dehydrated()
                    ->defaultItems(1)
                    ->addActionLabel('Add product')
            ]);
    }
}
