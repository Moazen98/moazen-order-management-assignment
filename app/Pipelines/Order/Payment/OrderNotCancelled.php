<?php

namespace App\Pipelines\Order\Payment;

use App\Enums\OrderStatus;
use Closure;
use Illuminate\Http\Response;

class OrderNotCancelled
{
    public function handle($order, Closure $next)
    {
        if ($order->status == OrderStatus::CANCELLED) {
            return [
                'status' => false,
                'message' => __('locale.The payment was declined'),
                'response_code' => Response::HTTP_FORBIDDEN,
                'data' => null
            ];
        }

        return $next($order);
    }
}
