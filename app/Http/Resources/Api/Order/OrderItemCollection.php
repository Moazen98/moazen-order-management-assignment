<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        return [
            'items' => $this->collection->map(function ($item) use ($request) {
                return (new OrderItemResource($item))->toArray($request);
            })
        ];

    }
}
