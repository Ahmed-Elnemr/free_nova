<?php

namespace App\Services;

use App\Repositories\Contracts\ServiceItemRepositoryContract;

class ServiceItemService extends MediaContentService
{
    public function __construct(ServiceItemRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    protected function slugSourceKey(): string
    {
        return 'title';
    }
}
