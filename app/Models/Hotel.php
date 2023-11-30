<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Hotel extends Model
{
    const STATUS = ['DISABLED', 'PENDING', 'ACTIVE'];

    /**
     * @return BelongsTo
     */
    public function hotelier(): BelongsTo
    {
        return $this->belongsTo(Hotelier::class, 'hotelier_id');
    }

    /**
     * @return BelongsToMany
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class);
    }

    /**
     * @return HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }

    /**
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        return exists($this->image) ? Storage::url($this->image) : null;
    }

    /**
     * @return string|null
     */
    public function getPolicyUrlAttribute(): ?string
    {
        return exists($this->policy) ? Storage::url($this->policy) : null;
    }

    /**
     * @return MorphMany
     */
    public function rates(): MorphMany
    {
        return $this->morphMany(Rate::class, 'ratable');
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(HotelImage::class, 'hotel_id');
    }
}
