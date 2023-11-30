<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Agency extends Model
{
    use HasApiTokens, Notifiable;

    const TYPE = ['PARENT', 'CHILD', 'INDEPENDENT'];
    const STATUS = ['DISABLED', 'PENDING', 'ACTIVE'];

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(AgencyProfile::class, 'agency_id');
    }

    /**
     * @return MorphOne
     */
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'owner');
    }
}
