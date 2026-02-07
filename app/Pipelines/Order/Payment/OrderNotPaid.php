<?php

namespace App\Pipelines\Order\Payment;

use App\Enums\OrderStatus;
use Closure;
use Illuminate\Http\Response;

class OrderNotPaid
{
    public function handle($order, Closure $next)
    {
        if ($order->status == OrderStatus::COMPLETED) {
            return [
                'status' => false,
                'message' => __('locale.This order has already paid'),
                'response_code' => Response::HTTP_FORBIDDEN,
                'data' => null
            ];
        }

        return $next($order);
    }
}
