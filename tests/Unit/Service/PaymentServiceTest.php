<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PaymentService;
use App\Models\User;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use App\Enums\PaymentStatus;

class PaymentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_success_changes_status_to_successful()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'status' => PaymentStatus::PENDING,
            'transaction_id' => 'tx_123',
        ]);

        $service = app(PaymentService::class);

        $result = $service->success('tx_123', $user);

        $this->assertTrue($result['status']);
        $this->assertEquals(PaymentStatus::SUCCESSFUL, $payment->fresh()->status);
    }
}
