<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\SelledProductsWidget;
use Faker\Provider\Base;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
	public function getWidgets(): array
	{
		return [
			SelledProductsWidget::class,
		];
	}

	public function getColumns(): int | array
	{
		return [
			'md' => 3,
			'xl' => 4,
		];
	}
}