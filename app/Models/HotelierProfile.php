<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HotelierProfile extends Model
{
    const CARD_STATUS = ['REJECTED', 'PENDING', 'ACCEPTED'];
    const STATUS = ['REJECTED', 'PENDING', 'ACCEPTED'];
    protected $appends = ['national_card_url', 'license_url'];

    /**
     * @return string|null
     */
    public function getNationalCardUrlAttribute(): ?string
    {
        return exists('national_card') ? Storage::url($this->national_card) : null;
    }

    /**
     * @return string|null
     */
    public function getLicenseUrlAttribute(): ?string
    {
        return exists('license') ? Storage::url($this->license) : null;
    }
}
