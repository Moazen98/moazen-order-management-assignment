<?php

namespace Database\Factories\Product;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'price'     => $this->faker->randomFloat(2, 10, 500),
            'is_active' => 1,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Product $product) {

            $faker = FakerFactory::create('en_US');

            foreach (config('translatable.locales') as $locale) {

                $product->translations()->create([
                    'locale'      => $locale,
                    'name'        => $faker->words(3, true),
                    'description' => $faker->sentence(10),
                ]);
            }
        });
    }


}
