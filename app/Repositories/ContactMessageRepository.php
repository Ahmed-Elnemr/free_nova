<?php

namespace App\Repositories;

use App\Enums\ContactMessageStatus;
use App\Http\Filters\ContactMessageFilter;
use App\Models\ContactMessage;
use App\Repositories\Contracts\ContactMessageRepositoryContract;
use Illuminate\Database\Eloquent\Model;

class ContactMessageRepository extends BaseRepository implements ContactMessageRepositoryContract
{
    /**
     * Resolve the model instance.
     */
    protected function resolveModel(): Model
    {
        return new ContactMessage;
    }

    /**
     * Resolve the filter instance.
     */
    protected function resolveFilter(): ?ContactMessageFilter
    {
        return new ContactMessageFilter(request());
    }

    public function countByStatus(ContactMessageStatus $status): int
    {
        return $this->newQuery()->where('status', $status->value)->count();
    }
}
