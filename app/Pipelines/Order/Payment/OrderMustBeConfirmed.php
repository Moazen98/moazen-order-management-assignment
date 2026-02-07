<?php

namespace App\Pipelines\Order\Payment;

use App\Enums\OrderStatus;
use Closure;
use Illuminate\Http\Response;

class OrderMustBeConfirmed
{
    public function handle($order, Closure $next)
    {
        if ($order->status !== OrderStatus::CONFIRMED) {
            return [
                'status' => false,
                'message' => __('locale.must be confirmed before payment'),
                'response_code' => Response::HTTP_FORBIDDEN,
                'data' => null
            ];
        }

        return $next($order);
    }
}
