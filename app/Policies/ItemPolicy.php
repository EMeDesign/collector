<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Policy of item model.
 *
 * @mixin \App\Models\Item
 */
class ItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any item.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can view item.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Item $item
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, Item $item): Response
    {
        $item->loadMissing('creator');

        return $user->is($item->creator)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    /**
     * Determine whether the user can create item.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the item.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Item $item
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Item $item): Response
    {
        $item->loadMissing('creator');

        return $user->is($item->creator)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    /**
     * Determine whether the user can delete the item.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Item $item
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Item $item): Response
    {
        $item->loadMissing('creator');

        return $user->is($item->creator)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }
}
