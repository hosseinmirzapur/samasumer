<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'url' => $this->url
        ];
    }
}
