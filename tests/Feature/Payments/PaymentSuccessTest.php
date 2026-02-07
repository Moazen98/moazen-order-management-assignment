<?php

namespace Tests\Feature\Payments;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order\Order;
use App\Models\Payment\Payment;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

class PaymentSuccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_mark_payment_as_successful()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => OrderStatus::CONFIRMED,
        ]);

        $payment = Payment::factory()->create([
            'order_id' => $order->id,
            'status' => PaymentStatus::PENDING,
            'transaction_id' => 'tx_success_123',
        ]);

        $response = $this
            ->withHeader('Authorization', "Bearer $token")
            ->withHeader('Accept-Language', 'en')
            ->postJson('/api/v1/payment/success', [
                'transaction_id' => $payment->transaction_id,
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::SUCCESSFUL,
        ]);
    }
}
