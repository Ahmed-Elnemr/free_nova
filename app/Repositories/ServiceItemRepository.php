<?php

namespace App\Repositories;

use App\Models\ServiceItem;
use App\Repositories\Concerns\FindsActiveOrdered;
use App\Repositories\Contracts\ServiceItemRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class ServiceItemRepository extends BaseRepository implements ServiceItemRepositoryContract
{
    use FindsActiveOrdered;

    protected function resolveModel(): Model
    {
        return new ServiceItem;
    }
}
