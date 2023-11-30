<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    const TYPE = ['SUITE', 'NORMAL'];
    const STATUS = ['DISABLED', 'PENDING', 'ACTIVE'];

    /**
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
