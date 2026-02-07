<?php

namespace App\Services\Gateways;

class PaymentGatewayFactory
{
    public static function make(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'paypal' => new PaypalGateway(),
            'credit_card' => new CreditCardGateway(),
            default  => throw new \InvalidArgumentException('Invalid payment gateway'),
        };
    }
}
