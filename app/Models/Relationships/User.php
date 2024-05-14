<?php

namespace App\Models\Relationships;

use App\Models\Construction;
use App\Models\Furniture;
use App\Models\Item;
use App\Models\Keyword;
use App\Models\Room;
use App\Models\UserKeyword;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Relationships of the user model.
 */
trait User
{
    /**
     * Get the constructions which created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function constructions(): HasMany
    {
        return $this->hasMany(Construction::class, 'user_id', 'id');
    }

    /**
     * Get the furniture which created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function furniture(): HasMany
    {
        return $this->hasMany(Furniture::class, 'user_id', 'id');
    }

    /**
     * Get the items which created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'user_id', 'id');
    }

    /**
     * Get the rooms which created by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'user_id', 'id');
    }

    /**
     * Get the keywords which used by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'user_keyword')->using(UserKeyword::class);
    }
}
