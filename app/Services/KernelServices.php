<?php

namespace App\Services;

class KernelServices
{
    public function __construct(
        public readonly ProductService $productService,
        public readonly AuthService $authService,
        public readonly OrderService $orderService,
        public readonly PaymentService $paymentService
    ) {
    }
}
