<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Passenger extends Model
{
    use HasApiTokens, Notifiable;

    const STATUS = [true, false];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(PassengerProfile::class, 'passenger_id');
    }

    /**
     * @return MorphOne
     */
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    /**
     * @return MorphMany
     */
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'owner');
    }
}
