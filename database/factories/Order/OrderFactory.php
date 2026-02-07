<?php

namespace Database\Factories\Order;

use App\Models\Order\Order;
use App\Models\User;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => OrderStatus::PENDING,
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
        ];
    }


    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatus::CONFIRMED,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => OrderStatus::CANCELLED,
        ]);
    }
}
