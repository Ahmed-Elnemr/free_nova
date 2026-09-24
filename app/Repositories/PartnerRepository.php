<?php

namespace App\Repositories;

use App\Models\Partner;
use App\Repositories\Concerns\FindsActiveOrdered;
use App\Repositories\Contracts\PartnerRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class PartnerRepository extends BaseRepository implements PartnerRepositoryContract
{
    use FindsActiveOrdered;

    protected function resolveModel(): Model
    {
        return new Partner;
    }
}
