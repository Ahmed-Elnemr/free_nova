<?php

namespace App\Filament\Concerns;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesFilamentResource
{
    abstract protected static function permissionPrefix(): string;

    public static function canViewAny(): bool
    {
        return static::canPermission('read');
    }

    public static function canCreate(): bool
    {
        return static::canPermission('create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::canPermission('update');
    }

    public static function canDelete(Model $record): bool
    {
        return static::canPermission('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::canPermission('delete');
    }

    protected static function canPermission(string $action): bool
    {
        $user = Filament::auth()->user();

        if (! $user) {
            return false;
        }

        $separator = config('permission.separator', ':');

        return $user->can(static::permissionPrefix().$separator.$action);
    }
}
