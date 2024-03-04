<?php

namespace App\Policies;

use App\Models\Furniture;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class FurniturePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    public function view(User $user, Furniture $furniture): Response
    {
        return $user->id === $furniture->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }

    public function update(User $user, Furniture $furniture): Response
    {
        return $user->id === $furniture->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    public function delete(User $user, Furniture $furniture): Response
    {
        return $user->id === $furniture->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }
}
