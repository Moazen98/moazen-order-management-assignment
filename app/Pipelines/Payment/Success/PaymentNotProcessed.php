<?php

namespace App\Pipelines\Payment\Success;

use App\Enums\PaymentStatus;
use Closure;
use Illuminate\Http\Response;

class PaymentNotProcessed
{
    public function handle($payment, Closure $next)
    {
        if ($payment->status === PaymentStatus::SUCCESSFUL) {
            return [
                'status' => false,
                'message' => __('locale.Payment already processed'),
                'response_code' => Response::HTTP_METHOD_NOT_ALLOWED,
                'data' => null
            ];
        }

        return $next($payment);
    }
}
