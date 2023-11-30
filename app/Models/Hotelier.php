<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Hotelier extends Model
{
    use HasApiTokens, Notifiable;
    const STATUS = [true, false];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(HotelierProfile::class, 'hotelier_id');
    }

    /**
     * @return HasOne
     */
    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class, 'hotelier_id');
    }

    /**
     * @return MorphOne
     */
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'owner');
    }
}
