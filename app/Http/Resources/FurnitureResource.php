<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Furniture */
class FurnitureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'image'       => $this->image,
            'description' => $this->description,
            'position'    => $this->position,
            'is_private'  => $this->is_private,
            'room_id'     => $this->room_id,
            'user_id'     => $this->user_id,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
