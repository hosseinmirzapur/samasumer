<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketerProfile extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function marketer(): BelongsTo
    {
        return $this->belongsTo(Marketer::class, 'marketer_id');
    }
}
