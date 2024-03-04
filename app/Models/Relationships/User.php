<?php

namespace App\Models\Relationships;

use App\Models\Furniture;
use App\Models\Room;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait User
{
    public function furniture()
    {
        return $this->hasMany(Furniture::class, 'user_id', 'id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'user_id', 'id');
    }
}
