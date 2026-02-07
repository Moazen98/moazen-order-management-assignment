<?php

namespace App\Http\Resources\Api\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        return [
            'id'        => $this->id,
            'price'     => $this->price,
            'is_active' => (bool) $this->is_active,
            'name'        => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
