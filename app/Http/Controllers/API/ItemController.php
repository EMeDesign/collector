<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Item::class);

        return ItemResource::collection(
            Item::query()
                ->where('user_id', auth()->user()->id)
                ->orderBy('position')
                ->get()
        );
    }

    public function show(Item $item)
    {
        $this->authorize('view', $item);

        return new ItemResource($item);
    }


    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json('Your item was deleted successfully!');
    }
}
