<?php

namespace App\Services;

use App\Repositories\Contracts\SiteMediaRepositoryContract;

class SiteMediaService extends MediaContentService
{
    protected bool $usesSlug = false;

    public function __construct(SiteMediaRepositoryContract $repository)
    {
        parent::__construct($repository);
    }
}
