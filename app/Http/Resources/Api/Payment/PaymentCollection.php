<?php

namespace App\Http\Resources\Api\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request=null)
    {
        return [
            'payments' => $this->collection->map(function ($payment) use ($request) {
                return (new PaymentResource($payment))->toArray($request);
            })
        ];
    }
}
