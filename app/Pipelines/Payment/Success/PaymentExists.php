<?php

namespace App\Pipelines\Payment\Success;

use Closure;
use Illuminate\Http\Response;

class PaymentExists
{
    public function handle($payment, Closure $next)
    {
        if (!$payment) {
            return [
                'status' => false,
                'message' => __('locale.Payment not found'),
                'response_code' => Response::HTTP_NOT_FOUND,
                'data' => null
            ];
        }

        return $next($payment);
    }
}
