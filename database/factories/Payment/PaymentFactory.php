<?php

namespace Database\Factories\Payment;

use App\Models\Payment\Payment;
use App\Models\Order\Order;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'status' => PaymentStatus::PENDING,
            'method' => 'paypal',
            'transaction_id' => $this->faker->uuid,
        ];
    }

    public function successful(): static
    {
        return $this->state(fn () => [
            'status' => PaymentStatus::SUCCESSFUL,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => PaymentStatus::FAILED,
        ]);
    }
}
