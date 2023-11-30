<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'room_count' => $this->room_count,
            'bed_count' => $this->bed_count,
            'capacity' => $this->capacity,
            'daily_price' => $this->daily_price,
            'refund_policy' => $this->refund_policy,
            'description' => $this->description,
            'discount' => $this->discount,
            'available_count' => $this->available_count
        ];
    }
}
