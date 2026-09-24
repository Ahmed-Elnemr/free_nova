<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface WhyUsPointRepositoryContract extends RepositoryContract
{
    public function activeOrdered(bool $withMedia = false): Collection;
}
