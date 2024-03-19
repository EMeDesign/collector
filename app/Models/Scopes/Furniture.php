<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait Furniture
{
    /**
     * Scope a query to only include furniture which created by authed user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeCreator(Builder $query): void
    {
        $query->where('user_id', '=', auth()->user()->id);
    }
}
