<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioWorkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->getTranslation('title', app()->getLocale()),
            'image' => $this->getFirstMediaUrl('image') ?: null,
            'sort_order' => $this->sort_order,
        ];
    }
}
