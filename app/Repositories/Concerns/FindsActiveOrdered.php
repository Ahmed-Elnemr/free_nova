<?php

namespace App\Repositories\Concerns;

use Illuminate\Database\Eloquent\Collection;

trait FindsActiveOrdered
{
    public function countActive(): int
    {
        return $this->newQuery()->where('is_active', true)->count();
    }

    public function activeOrdered(bool $withMedia = false): Collection
    {
        $query = $this->newQuery()->where('is_active', true);

        if ($withMedia) {
            $query->with('media');
        }

        return $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
