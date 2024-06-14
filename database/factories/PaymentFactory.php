<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Membuat atau mengambil ID dari instance User
            'payment_method' => $this->faker->randomElement(['debit_card', 'credit_card', 'e_wallet', 'bank_transfer', 'paypal']), // Metode pembayaran acak
            'payment_details' => $this->faker->optional()->text(50), // Detail pembayaran acak, opsional
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
