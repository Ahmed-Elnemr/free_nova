<?php

namespace App\Models;

use App\Traits\HasOtps;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens;
    use HasOtps;
    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'avatar',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function getAvatarAttribute(): ?string
    {
        $media = $this->getFirstMedia('avatar');

        return $media?->getFullUrl();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->is_active;
    }

    public function getDefaultGuardName(): string
    {
        return 'admin';
    }
}
