<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FurnitureResource;
use App\Models\Furniture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FurnitureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('viewAny', Furniture::class);

        return FurnitureResource::collection(
            Furniture::query()
                ->where('user_id', auth()->user()->id)
                ->orderBy('position')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \App\Http\Resources\FurnitureResource
     */
    public function store(Request $request)
    {
        $this->authorize('create', Furniture::class);

        $data = $request->validate([
            'name'        => ['required', 'string', 'between:1,255'],
            'image'       => ['nullable', 'image',  'max:1024'],
            'description' => ['required', 'string', 'min:3'],
            'position'    => ['required', 'int',    'numeric'],
            'is_private'  => ['required', 'bool'],
            'room_id'     => ['required', 'int',    'numeric', 'exists:rooms,id']
        ]);

        return new FurnitureResource($request->user()->furniture()->create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Furniture $furniture
     *
     * @return \App\Http\Resources\FurnitureResource
     */
    public function show(Furniture $furniture)
    {
        $this->authorize('view', $furniture);

        return new FurnitureResource($furniture);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Furniture $furniture
     *
     * @return \App\Http\Resources\FurnitureResource
     */
    public function update(Request $request, Furniture $furniture)
    {
        $this->authorize('update', $furniture);

        $data = $request->validate([
            'name'        => ['required', 'string', 'between:1,255'],
            'image'       => ['nullable', 'image',  'max:1024'],
            'description' => ['required', 'string', 'min:3'],
            'position'    => ['required', 'int',    'numeric'],
            'is_private'  => ['required', 'bool'],
            'room_id'     => ['required', 'int',    'numeric', 'exists:rooms,id']
        ]);

        $furniture->update($data);

        return new FurnitureResource($furniture);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Furniture $furniture
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Furniture $furniture): JsonResponse
    {
        $this->authorize('delete', $furniture);

        $furniture->delete();

        return response()->json('Your furniture was deleted successfully!');
    }
}
