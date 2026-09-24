<?php

namespace App\Repositories;

use App\Models\PortfolioWork;
use App\Repositories\Concerns\FindsActiveOrdered;
use App\Repositories\Contracts\PortfolioWorkRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class PortfolioWorkRepository extends BaseRepository implements PortfolioWorkRepositoryContract
{
    use FindsActiveOrdered;

    protected function resolveModel(): Model
    {
        return new PortfolioWork;
    }
}
