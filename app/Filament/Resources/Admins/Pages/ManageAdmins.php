<?php

namespace App\Filament\Resources\Admins\Pages;

use App\Filament\Resources\Admins\AdminResource;
use App\Services\AdminService;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAdmins extends ManageRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->using(function (array $data) {
                return app(AdminService::class)->create(AdminResource::prepareAdminData($data));
            }),
        ];
    }
}
