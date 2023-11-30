<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray($request)
    {
        [$main_avg_price, $discount_avg_price, $avg_discount] = $this->handleRoomAvgPrice();
        return [
            'name' => $this->name,
            'image_url' => $this->image_url,
            'rate' => $this->handleHotelRate(),
            'rooms' => RoomResource::collection($this->rooms()->where('status', 'ACTIVE')->get()),
            'facilities' => FacilityResource::collection($this->facilities),
            'description' => $this->description,
            'main_avg_price' => $main_avg_price,
            'discount_avg_price' => $discount_avg_price,
            'avg_discount' => $avg_discount,
            'time_start' => $this->time_start,
            'time_end' => $this->time_end,
            'has_breakfast' => $this->has_breakfast,
            'has_lunch' => $this->has_lunch,
            'has_dinner' => $this->has_dinner,
            'address' => $this->address,
            'policy_url' => $this->policy_url,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'images' => HotelImageResource::collection($this->images)
        ];
    }

    /**
     * @return mixed
     */
    protected function handleHotelRate(): mixed
    {
        return $this->rates()->avg('amount') ?? 0;
    }

    /**
     * @return array
     */
    protected function handleRoomAvgPrice(): array
    {
        $main_avg = $this->rooms()->avg('daily_price');
        $rooms = $this->rooms;
        $room_count = $rooms->count();
        $price = 0;
        $discount = 0;
        foreach ($rooms as $room) {
            $price += $room->daily_price * (100 - ($room->discount / 100));
            $discount += $room->discount;
        }
        $discount_avg = $price / $room_count;
        $avg_discount = $discount / $room_count / 100;
        return [$main_avg, $discount_avg, $avg_discount];
    }
}
