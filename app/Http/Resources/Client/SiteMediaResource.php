<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'key' => $this->key?->value ?? $this->key,
            'alt' => $this->getTranslation('alt', app()->getLocale()),
            'image' => $this->getFirstMediaUrl('image') ?: null,
        ];
    }
}
