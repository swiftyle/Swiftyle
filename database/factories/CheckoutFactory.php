<?php

namespace Database\Factories;

use App\Models\Checkout;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Checkout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cart_id' => function () {
                return \App\Models\Cart::factory()->create()->id;
            },
            'payment_id' => function () {
                return \App\Models\Payment::factory()->create()->id;
            },
            'address_id' => function () {
                return \App\Models\Address::factory()->create()->id;
            },
            'courier_id' => function () {
                return \App\Models\Courier::factory()->create()->id;
            },
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
