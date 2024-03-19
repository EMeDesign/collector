<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait Item
{
    /**
     * Scope a query to only include items which created by authed user.
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
     * Scope a query to only include unexpired items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopeExpired(Builder $query): void
    {
        $query->where('expired_at', '<', Carbon::now());
    }

    /**
     * Scope a query to only include private items.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return void
     */
    public function scopePrivate(Builder $query): void
    {
        $query->where('is_private', '=', true);
    }

    /**
     * Scope a query to only include items which name like search keywords.
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
     * Scope a query to only include items which description like search keywords.
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

    /**
     * Scope a query to only include items which quantity less than the given value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $quantities
     * @param bool $strict
     *
     * @return void
     */
    public function scopeOfQuantityLessThan(Builder $query, int $quantities, bool $strict = false): void
    {
        $query->when(
            value: $strict,
            callback: fn (Builder $query) => $query->where('quantity', '<', $quantities),
            default: fn (Builder $query) => $query->where('quantity', '<=', $quantities),
        );
    }

    /**
     * Scope a query to only include items which quantity more than the given value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $quantities
     * @param bool $strict
     *
     * @return void
     */
    public function scopeOfQuantityMoreThan(Builder $query, int $quantities, bool $strict = false): void
    {
        $query->when(
            value: $strict,
            callback: fn (Builder $query) => $query->where('quantity', '>', $quantities),
            default: fn (Builder $query) => $query->where('quantity', '>=', $quantities),
        );
    }
}
