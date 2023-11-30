<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Facility extends Model
{
    /**
     * @return BelongsToMany
     */
    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class);
    }

    /**
     * @return string|null
     */
    public function getIconUrlAttribute(): ?string
    {
        return exists($this->icon) ? Storage::url($this->icon) : null;
    }
}
