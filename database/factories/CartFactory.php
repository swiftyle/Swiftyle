<?php

namespace Database\Factories;

use App\Models\AppCoupon;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cart::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'app_coupon_id' => AppCoupon::factory(),
            'discount' => $this->faker->randomFloat(2, 0, 100),  // Asumsi diskon antara 0 dan 100
            'total_discount' => $this->faker->randomFloat(2, 0, 200),  // Asumsi total diskon antara 0 dan 200
            'total_price' => $this->faker->randomFloat(2, 0, 1000),  // Asumsi total harga antara 0 dan 1000
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ];
    }
}
