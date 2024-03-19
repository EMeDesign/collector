<?php

namespace App\Models\Relationships;

use App\Models\Furniture;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Item
{

    /**
     * Get the creator of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'creator');
    }

    /**
     * Get the furniture of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furniture(): BelongsTo
    {
        return $this->belongsTo(Furniture::class, 'furniture_id', 'id');
    }

    /**
     * Get the Unit of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
