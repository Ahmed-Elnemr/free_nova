<?php

namespace App\Repositories\Contracts;

use App\Enums\SiteMediaKey;
use Illuminate\Database\Eloquent\Model;

interface SiteMediaRepositoryContract extends RepositoryContract
{
    public function findByKey(SiteMediaKey $key): ?Model;
}
