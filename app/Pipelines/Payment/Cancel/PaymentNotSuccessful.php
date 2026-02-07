<?php

namespace App\Pipelines\Payment\Cancel;

use App\Enums\PaymentStatus;
use Closure;
use Illuminate\Http\Response;

class PaymentNotSuccessful
{
    public function handle($payment, Closure $next)
    {
        if ($payment->status === PaymentStatus::SUCCESSFUL) {
            return [
                'status' => false,
                'message' => __('locale.Cannot cancel successful payment'),
                'response_code' => Response::HTTP_METHOD_NOT_ALLOWED,
                'data' => null
            ];
        }

        return $next($payment);
    }
}
