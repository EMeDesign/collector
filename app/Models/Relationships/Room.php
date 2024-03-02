<?php

namespace App\Models\Relationships;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Room
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'creator');
    }
}
