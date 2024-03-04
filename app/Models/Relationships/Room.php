<?php

namespace App\Models\Relationships;

use App\Models\Furniture;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Room
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'creator');
    }

    public function furniture()
    {
        return $this->hasMany(Furniture::class, 'room_id', 'id');
    }
}
