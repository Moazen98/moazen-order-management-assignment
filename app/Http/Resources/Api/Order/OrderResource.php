<?php

namespace App\Http\Resources\Api\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user_id'     => $this->user_id,
            'total_amount'     => $this->total_amount,
            'status'     => $this->status,
            'items'     => (new OrderItemCollection($this->items))->toArray($request)['items'],
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
