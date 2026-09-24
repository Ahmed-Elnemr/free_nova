<?php

namespace App\Filament\Resources\ServiceItems\Pages;

use App\Filament\Resources\ServiceItems\ServiceItemResource;
use App\Services\ServiceItemService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageServiceItems extends ManageRecords
{
    protected static string $resource = ServiceItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->using(fn (array $data) => app(ServiceItemService::class)->create($data)),
        ];
    }
}
