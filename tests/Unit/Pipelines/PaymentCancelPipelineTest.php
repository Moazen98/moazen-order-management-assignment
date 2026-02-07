<?php

namespace Tests\Unit\Pipelines;

use Tests\TestCase;
use App\Enums\PaymentStatus;
use App\Models\Payment\Payment;
use App\Pipelines\Payment\Cancel\PaymentNotSuccessful;

class PaymentCancelPipelineTest extends TestCase
{
    public function test_cannot_cancel_successful_payment()
    {
        $payment = new Payment([
            'status' => PaymentStatus::SUCCESSFUL,
        ]);

        $pipe = new PaymentNotSuccessful();

        $result = $pipe->handle($payment, fn ($p) => $p);

        $this->assertFalse($result['status']);
        $this->assertEquals(
            __('locale.Cannot cancel successful payment'),
            $result['message']
        );
    }
}
