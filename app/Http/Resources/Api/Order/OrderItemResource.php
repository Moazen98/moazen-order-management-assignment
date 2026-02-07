<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id'     => $this->order_id,
            'product_id'     => $this->product_id,
            'product_name'     => $this->product?->name,
            'price'     => $this->price,
            'quantity'     => $this->quantity,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
