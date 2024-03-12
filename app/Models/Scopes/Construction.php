<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait Construction
{
    /**
     * Scope a query to only include constructions which created by authed user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeCreator(Builder $query): void
    {
        $query->where('user_id', '=', auth()->user()->id);
    }

    /**
     * Scope a query to only include constructions which name like search keywords.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     *
     * @return void
     */
    public function scopeOfName(Builder $query, string $search): void
    {
        $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Scope a query to only include constructions which description like search keywords.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     *
     * @return void
     */
    public function scopeOfDescription(Builder $query, string $search): void
    {
        $query->where('description', 'like', "%{$search}%");
    }
}
