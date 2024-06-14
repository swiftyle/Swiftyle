<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['percentage_discount', 'fixed_discount', 'buy_one_get_one']);
        $discountAmount = $type === 'percentage_discount' ? $this->faker->randomFloat(2, 5, 50) : null;
        $discountPercentage = $type === 'fixed_discount' ? $this->faker->randomFloat(2, 5, 50) : null;

        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'type' => $type,
            'discount_amount' => $discountAmount,
            'discount_percentage' => $discountPercentage,
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
        ];
    }
}
