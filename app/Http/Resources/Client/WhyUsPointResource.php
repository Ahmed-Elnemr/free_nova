<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WhyUsPointResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'body' => $this->getTranslation('body', app()->getLocale()),
            'sort_order' => $this->sort_order,
        ];
    }
}
