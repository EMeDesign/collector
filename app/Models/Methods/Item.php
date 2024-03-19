<?php

namespace App\Models\Methods;

use App\Models\Item as ItemModel;
use App\Models\User;

trait Item
{
    public function isCreator(?User $user = null): bool
    {
        if (is_null($user)) {
            $user = auth()->user();
        }

        return $user->is($this->creator);
    }

    public function getQuantityWithUnit(): string
    {
        return $this->transQuantityToString()->quantity;
    }

    public function transQuantityToString(): ItemModel
    {
        $this->loadMissing('unit');

        $this->quantity = $this->quantity . ' ' . $this->unit->name;

        return $this;
    }
}
