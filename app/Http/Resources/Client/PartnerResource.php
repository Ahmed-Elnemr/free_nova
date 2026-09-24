<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->getTranslation('name', app()->getLocale()),
            'logo' => $this->getFirstMediaUrl('logo') ?: null,
            'sort_order' => $this->sort_order,
        ];
    }
}
