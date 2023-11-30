<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AgencyProfile extends Model
{
    /**
     * @return BelongsTo
     */
    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    /**
     * @return string|null
     */
    public function getAgencyPolicyUrlAttribute(): ?string
    {
        return exists($this->agency_policy) ? Storage::url($this->agency_policy) : null;
    }

    /**
     * @return string|null
     */
    public function getNationalCardUrlAttribute(): ?string
    {
        return exists($this->national_card) ? Storage::url($this->national_card) : null;
    }
}
