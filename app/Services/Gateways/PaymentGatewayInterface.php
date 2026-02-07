<?php

namespace App\Services\Gateways;

use App\Models\Order\Order;

interface PaymentGatewayInterface
{
    public function pay(Order $order);
}
