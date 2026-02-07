<?php

namespace App\Services\Gateways;

use App\Models\Order\Order;

class CreditCardGateway implements PaymentGatewayInterface
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('payments.gateways.credit_cart');
    }


    public function pay(Order $order)
    {
        $clientId = $this->config['key'];
        $secret   = $this->config['secret'];

        //TODO: logic link with thirdpart payment gateway should be here
        return [
            'status' => 'successful',
            'transaction_id' => uniqid('cc_'),
        ];
    }
}
