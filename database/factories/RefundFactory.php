<?php

namespace Database\Factories;

use App\Models\Refund;
use App\Models\RefundRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class RefundFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Refund::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'refund_request_id' => RefundRequest::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'status' => 'refunded',
        ];
    }
}

