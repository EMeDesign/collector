<?php

namespace App\Models\Methods;

use App\Models\User;

trait Room
{
    public function isCreator(?User $user = null): bool
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        return $this->creator->id === $user?->id;
    }
}
