<?php

namespace App\Filament\Resources\Products\Tables;

use App\Enums\ExportFormat;
use App\Services\Export\Exporters\ProductExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('status.title')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('currency'),
                TextColumn::make('stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Action::make('export')
                    ->label('Exportar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->schema([
                        Select::make('format')
                            ->options(ExportFormat::getArrayOptions())
                            ->default('json')
                            ->required(),
                    ])
                    ->action(function(array $data) {
                        $exporter = new ProductExporter();
                        $result = $exporter->exportWithFormat($data['format']);
                        
                        return response()->streamDownload(
                            fn() => print($result['content']),
                            $result['filename'],
                            ['Content-Type' => $result['contentType']]
                        );
                    }),
            ]);
    }
}
