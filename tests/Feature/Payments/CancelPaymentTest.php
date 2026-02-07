<?php

namespace Tests\Feature\Payments;

use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class CancelPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_cancel_pending_payment()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'status' => 'pending',
            'transaction_id' => 'tx_555',
        ]);

        $response = $this
            ->withHeader('Accept-Language', 'en')
            ->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/payment/cancel', [
                'transaction_id' => $payment->transaction_id,
            ]);

        $response->assertStatus(200);
    }

}
