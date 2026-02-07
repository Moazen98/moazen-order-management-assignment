<?php

namespace Tests\Unit\PaymentGateways;

use Tests\TestCase;
use App\Services\Gateways\PaypalGateway;
use App\Models\Order\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;


class PaypalGatewayTest extends TestCase
{

    use RefreshDatabase;

    public function test_paypal_gateway_returns_success_response()
    {
        config()->set('payments.gateways.paypal', [
            'key' => 'test_client',
            'secret' => 'test_secret',
        ]);

        $gateway = new PaypalGateway();

        $order = Order::factory()->make([
            'total_amount' => 100,
        ]);

        $result = $gateway->pay($order);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertEquals('successful', $result['status']);
    }
}
