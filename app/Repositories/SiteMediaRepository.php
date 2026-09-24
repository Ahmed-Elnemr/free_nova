<?php

namespace App\Repositories;

use App\Enums\SiteMediaKey;
use App\Models\SiteMedia;
use App\Repositories\Contracts\SiteMediaRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class SiteMediaRepository extends BaseRepository implements SiteMediaRepositoryContract
{
    protected function resolveModel(): Model
    {
        return new SiteMedia;
    }

    public function findByKey(SiteMediaKey $key): ?Model
    {
        return $this->newQuery()
            ->with('media')
            ->where('key', $key->value)
            ->where('is_active', true)
            ->first();
    }
}
