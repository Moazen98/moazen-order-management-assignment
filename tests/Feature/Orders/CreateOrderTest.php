<?php

namespace Tests\Feature\Orders;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product\Product;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_order()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $product = Product::factory()->create([
            'price' => 50,
        ]);

        $response = $this
            ->withHeader('Accept-Language', 'en')
            ->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/v1/order/store', [
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 2,
                    ],
                ],
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
        ]);
    }
}
