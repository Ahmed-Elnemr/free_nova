<?php

namespace App\Filament\Resources\SiteMedia\Pages;

use App\Filament\Resources\SiteMedia\SiteMediaResource;
use App\Services\SiteMediaService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteMedia extends ManageRecords
{
    protected static string $resource = SiteMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->using(fn (array $data) => app(SiteMediaService::class)->create($data)),
        ];
    }
}
