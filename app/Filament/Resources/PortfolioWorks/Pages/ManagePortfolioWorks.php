<?php

namespace App\Filament\Resources\PortfolioWorks\Pages;

use App\Filament\Resources\PortfolioWorks\PortfolioWorkResource;
use App\Services\PortfolioWorkService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePortfolioWorks extends ManageRecords
{
    protected static string $resource = PortfolioWorkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->using(fn (array $data) => app(PortfolioWorkService::class)->create($data)),
        ];
    }
}
