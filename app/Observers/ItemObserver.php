<?php

namespace App\Observers;

use App\Models\Item;

class ItemObserver
{
    /**
     * Handle the Item "deleting" event.
     */
    public function deleting(Item $item): void
    {
        $item->keywords()->detach();
    }
}
