<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Room::class);

        return RoomResource::collection(
            Room::query()
                ->Creator()
                ->orderBy('position')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \App\Http\Resources\RoomResource
     */
    public function store(Request $request): RoomResource
    {
        $this->authorize('create', Room::class);

        $data = $request->validate([
            'name'            => ['required', 'string', 'between:1,255'],
            'image'           => ['nullable', 'image', 'max:1024'],
            'description'     => ['required', 'string', 'min:3'],
            'position'        => ['required', 'int', 'numeric'],
            'construction_id' => ['required', 'int', 'numeric', 'exists:constructions,id']
        ]);

        return new RoomResource($request->user()->rooms()->create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Room $room
     *
     * @return \App\Http\Resources\RoomResource
     */
    public function show(Room $room): RoomResource
    {
        $this->authorize('view', $room);

        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Room $room
     *
     * @return \App\Http\Resources\RoomResource
     */
    public function update(Request $request, Room $room): RoomResource
    {
        $this->authorize('update', $room);

        $data = $request->validate([
            'name'            => ['required', 'string', 'between:1,255'],
            'image'           => ['nullable', 'image', 'max:1024'],
            'description'     => ['required', 'string', 'min:3'],
            'position'        => ['required', 'int', 'numeric'],
            'construction_id' => ['required', 'int', 'numeric', 'exists:constructions,id']
        ]);

        $room->update($data);

        return new RoomResource($room);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Room $room
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Room $room): JsonResponse
    {
        $this->authorize('delete', $room);

        $room->delete();

        return response()->json('Your room was deleted successfully!');
    }
}
