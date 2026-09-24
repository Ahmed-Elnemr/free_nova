<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PartnerRepositoryContract extends RepositoryContract
{
    public function countActive(): int;

    public function activeOrdered(bool $withMedia = false): Collection;
}
