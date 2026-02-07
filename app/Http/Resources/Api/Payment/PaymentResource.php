<?php

namespace App\Http\Resources\Api\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'order_id'        => $this->order_id,
            'status'     => $this->status,
            'method'        => $this->method,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
