<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HotelImage extends Model
{
    /**
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    /**
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        return exists($this->image) ? Storage::url($this->image) : null;
    }
}
