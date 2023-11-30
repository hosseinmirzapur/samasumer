<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class Marketer extends Model
{
    use HasApiTokens, Notifiable;

    const STATUS = [true, false];

    /**
     * @return MorphOne
     */
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'owner');
    }

    /**
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(MarketerProfile::class, 'marketer_id');
    }

    /**
     * @return string|null
     */
    public function getNationalCardUrlAttribute(): ?string
    {
        return exists($this->national_card) ? Storage::url($this->national_card) : null;
    }

    /**
     * @return MorphMany
     */
    public function agencies(): MorphMany
    {
        return $this->morphMany(Agency::class, 'child');
    }

    /**
     * @return MorphMany
     */
    public function hoteliers(): MorphMany
    {
        return $this->morphMany(Hotelier::class, 'child');
    }
}
