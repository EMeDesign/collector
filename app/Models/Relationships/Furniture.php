<?php

namespace App\Models\Relationships;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Furniture
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'creator');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id', 'room');
    }
}
