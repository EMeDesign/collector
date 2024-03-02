<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RoomPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): Response
    {
        return Response::allow();
    }

    public function view(User $user, Room $room): Response
    {
        return $user->id === $room->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }

    public function update(User $user, Room $room): Response
    {
        return $user->id === $room->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }

    public function delete(User $user, Room $room): Response
    {
        return $user->id === $room->user_id
            ? Response::allow()
            : Response::denyWithStatus('403', 'Permission Denied');
    }
}
