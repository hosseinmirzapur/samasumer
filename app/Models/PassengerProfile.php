<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassengerProfile extends Model
{
    const GENDERS = ['MALE', 'FEMALE'];
    const CARD_STATUS = ['REJECTED', 'NORMAL', 'ACCEPTED'];
    /**
     * @return BelongsTo
     */
    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class, 'passenger_id');
    }
}
