<?php

namespace App\Models\Relationships;

use App\Models\Furniture;
use App\Models\Keyword;
use App\Models\Unit;
use App\Models\User;
use App\Models\UserKeyword;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * Get the owner of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id', 'owner')
            ->withDefault([
                'name' => self::creator()->value('name')
            ]);
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

    /**
     * Get the Keywords of the item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class, 'user_keyword', 'item_id', 'keyword_id')
            ->using(UserKeyword::class);
    }
}
