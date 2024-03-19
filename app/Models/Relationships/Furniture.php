<?php

namespace App\Models\Relationships;

use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Furniture
{
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'creator');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'furniture_id', 'id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id', 'room');
    }
}
