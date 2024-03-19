<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Item::class);

        return ItemResource::collection(Item::all());
    }

    public function store(Request $request)
    {
        $this->authorize('create', Item::class);

        $data = $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['required', 'integer'],
            'image' => ['nullable'],
        ]);

        return new ItemResource(Item::create($data));
    }

    public function show(Item $item)
    {
        $this->authorize('view', $item);

        return new ItemResource($item);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $data = $request->validate([
            'name' => ['required'],
            'description' => ['required'],
            'quantity' => ['required', 'integer'],
            'image' => ['nullable'],
        ]);

        $item->update($data);

        return new ItemResource($item);
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json();
    }
}
