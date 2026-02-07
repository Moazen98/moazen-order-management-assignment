<?php

namespace Tests\Feature\Payments;

use App\Models\Order\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class PayOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_pay_confirmed_order()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'status' => 'confirmed',
        ]);

        $response = $this
            ->withHeader('Accept-Language', 'en')
            ->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/order/pay', [
                'order_id' => $order->id,
                'payment_method' => 'paypal',
            ]);

        $response->assertStatus(403); // unconfirmed order
    }
}
