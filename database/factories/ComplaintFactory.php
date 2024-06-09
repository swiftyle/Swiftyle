<?php

namespace Database\Factories;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Complaint::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Menggunakan factory untuk User
            'order_id' => Order::factory(), // Menggunakan factory untuk Order
            'description' => $this->faker->paragraph, // Deskripsi keluhan acak
            'status' => $this->faker->randomElement(['pending', 'resolved', 'rejected']), // Status acak
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
