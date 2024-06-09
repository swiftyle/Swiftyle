<?php

namespace Database\Factories;

use App\Models\OrderHistory;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(), // Membuat atau mengambil ID dari instance Order
            'description' => $this->faker->optional()->text, // Deskripsi acak, opsional
            'status' => $this->faker->randomElement(['done', 'cancelled', 'refunded']), // Status acak
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
