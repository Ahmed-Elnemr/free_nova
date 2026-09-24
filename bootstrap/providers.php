<?php

use App\Providers\ApiResponseServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\MediaUploadServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\TelescopeServiceProvider;

return [
    ApiResponseServiceProvider::class,
    AppServiceProvider::class,
    AdminPanelProvider::class,
    MediaUploadServiceProvider::class,
    RepositoryServiceProvider::class,
    TelescopeServiceProvider::class,
];
