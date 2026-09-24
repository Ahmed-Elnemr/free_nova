<?php

namespace App\Services;

use App\Repositories\Contracts\PartnerRepositoryContract;

class PartnerService extends MediaContentService
{
    protected string $mediaCollection = 'logo';

    protected string $mediaField = 'logo';

    public function __construct(PartnerRepositoryContract $repository)
    {
        parent::__construct($repository);
    }
}
