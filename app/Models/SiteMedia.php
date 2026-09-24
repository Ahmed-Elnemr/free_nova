<?php

namespace App\Models;

use App\Enums\SiteMediaKey;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SiteMedia extends Model implements HasMedia
{
    use HasTranslations;
    use InteractsWithMedia;

    public array $translatable = [
        'alt',
    ];

    protected $fillable = [
        'key',
        'alt',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'key' => SiteMediaKey::class,
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
