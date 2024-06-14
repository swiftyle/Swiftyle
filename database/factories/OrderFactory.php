<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Membuat atau mengambil ID dari instance User
            'shipping_id' => Shipping::factory(), // Membuat atau mengambil ID dari instance Shipping
            'status' => $this->faker->randomElement(['delivered', 'recieved', 'reviewed']), // Status acak
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
