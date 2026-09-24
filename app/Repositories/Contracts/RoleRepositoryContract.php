<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RoleRepositoryContract extends RepositoryContract
{
    public function forGuard(string $guard): Collection;

    /**
     * Find a role that has exactly the same set of permissions.
     * Optionally exclude a role by ID (used during updates).
     */
    public function findRoleWithSamePermissions(array $permissionIds, ?int $ignoreId = null): ?Model;
}
