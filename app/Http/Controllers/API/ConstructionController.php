<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ConstructionResource;
use App\Models\Construction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Construction API Controller.
 */
class ConstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Construction::class);

        return ConstructionResource::collection(
            Construction::query()
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
     * @return \App\Http\Resources\ConstructionResource
     */
    public function store(Request $request): ConstructionResource
    {
        $this->authorize('create', Construction::class);

        $data = $request->validate([
            'name'        => ['required', 'string', 'between:1,255'],
            'image'       => ['nullable', 'image',  'max:1024'],
            'location'    => ['required', 'string', 'between:1,255'],
            'description' => ['required', 'string', 'min:3'],
            'position'    => ['required', 'int',    'numeric'],
        ]);

        return new ConstructionResource(Construction::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Construction $construction
     *
     * @return \App\Http\Resources\ConstructionResource
     */
    public function show(Construction $construction)
    {
        $this->authorize('view', $construction);

        return new ConstructionResource($construction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Construction $construction
     *
     * @return \App\Http\Resources\ConstructionResource
     */
    public function update(Request $request, Construction $construction)
    {
        $this->authorize('update', $construction);

        $data = $request->validate([
            'name'        => ['required', 'string', 'between:1,255'],
            'image'       => ['nullable', 'image',  'max:1024'],
            'location'    => ['required', 'string', 'between:1,255'],
            'description' => ['required', 'string', 'min:3'],
            'position'    => ['required', 'int',    'numeric'],
        ]);

        if (empty($data['image'])) {
            unset($data['image']);
        }

        $construction->update($data);

        return new ConstructionResource($construction);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Construction $construction
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Construction $construction)
    {
        $this->authorize('delete', $construction);

        $construction->delete();

        return response()->json('Your construction was deleted successfully!');
    }
}
