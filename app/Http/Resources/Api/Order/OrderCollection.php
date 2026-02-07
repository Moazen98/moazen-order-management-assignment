<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request=null)
    {
        return [
            'orders' => $this->collection->map(function ($order) use ($request) {
                return (new OrderResource($order))->toArray($request);
            })
        ];
    }
}
