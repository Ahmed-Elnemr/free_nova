<?php

namespace App\Services;

use App\Repositories\Contracts\PortfolioWorkRepositoryContract;

class PortfolioWorkService extends MediaContentService
{
    public function __construct(PortfolioWorkRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    protected function slugSourceKey(): string
    {
        return 'title';
    }
}
