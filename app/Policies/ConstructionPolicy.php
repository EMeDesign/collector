<?php

namespace App\Policies;

use App\Models\Construction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Policy of construction model.
 */
class ConstructionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any construction.
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
     * Determine whether the user can view construction.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Construction $construction
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function view(User $user, Construction $construction): Response
    {
        return $construction->creator()->is($user)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    /**
     * Determine whether the user can create construction.
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
     * Determine whether the user can update the construction.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Construction $construction
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function update(User $user, Construction $construction): Response
    {
        return $construction->creator()->is($user)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    /**
     * Determine whether the user can delete the construction.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Construction $construction
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Construction $construction): Response
    {
        return $construction->creator()->is($user)
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }
}
