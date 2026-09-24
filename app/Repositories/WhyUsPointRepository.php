<?php

namespace App\Repositories;

use App\Models\WhyUsPoint;
use App\Repositories\Concerns\FindsActiveOrdered;
use App\Repositories\Contracts\WhyUsPointRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class WhyUsPointRepository extends BaseRepository implements WhyUsPointRepositoryContract
{
    use FindsActiveOrdered;

    protected function resolveModel(): Model
    {
        return new WhyUsPoint;
    }
}
