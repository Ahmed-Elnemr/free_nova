<?php

namespace App\Repositories\Contracts;

use App\Enums\ContactMessageStatus;

interface ContactMessageRepositoryContract extends RepositoryContract
{
    public function countByStatus(ContactMessageStatus $status): int;
}
