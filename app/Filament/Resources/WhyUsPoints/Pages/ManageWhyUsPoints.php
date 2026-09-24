<?php

namespace App\Filament\Resources\WhyUsPoints\Pages;

use App\Filament\Resources\WhyUsPoints\WhyUsPointResource;
use App\Services\WhyUsPointService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWhyUsPoints extends ManageRecords
{
    protected static string $resource = WhyUsPointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->using(fn (array $data) => app(WhyUsPointService::class)->create($data)),
        ];
    }
}
