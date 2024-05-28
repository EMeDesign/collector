<?php

namespace App\Http\Resources;

use Illuminate\Foundation\Vite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Item */
class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'image'        => $this->image,
            'description'  => $this->description,
            'quantity'     => $this->quantity,
            'is_private'   => $this->is_private,
            'furniture_id' => $this->furniture_id,
            'category_id'  => $this->category_id,
            'unit_id'      => $this->unit_id,
            'user_id'      => $this->user_id,
            'owner_id'     => $this->owner_id,
            'obtained_at'  => $this->obtained_at,
            'expired_at'   => $this->expired_at,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
