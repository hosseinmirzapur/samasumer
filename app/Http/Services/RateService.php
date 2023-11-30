<?php


namespace App\Http\Services;


use App\Models\Hotel;
use App\Models\Rate;

class RateService
{
    public function rateHotel($hotel_id, $amount)
    {
        $user_id = currentUser()->id ?? null;
        $user_type = exists(currentUser()) ? get_class(currentUser()) : null;

        Rate::query()->updateOrCreate([
            'ip_address' => request()->ip(),
            'ratable_id' => $hotel_id,
            'ratable_type' => Hotel::class
        ], [
            'ip_address' => request()->ip(),
            'ratable_id' => $hotel_id,
            'ratable_type' => Hotel::class,
            'user_id' => $user_id,
            'user_type' => $user_type,
            'amount' => $amount
        ]);
    }
}
