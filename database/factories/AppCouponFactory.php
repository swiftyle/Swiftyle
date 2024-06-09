<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppCoupon>
 */
class AppCouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Ambil ID pengguna yang memiliki peran admin dari kolom role
        $adminUser = User::where('role', 'admin')->inRandomOrder()->first();

        return [
            'user_id' => $adminUser ? $adminUser->id : null,
            'name' => $this->faker->name(), // Nama kupon
            'code' => strtoupper($this->faker->unique()->lexify('COUPON-??????')), // Kode kupon unik dengan pola
            'type' => $this->faker->randomElement(['percentage_discount', 'fixed_discount']), // Jenis diskon acak
            'discount_amount' => $this->faker->randomFloat(2, 5, 50), // Jumlah diskon antara 5 dan 50
            'max_uses' => $this->faker->boolean(80) ? $this->faker->numberBetween(1, 100) : null, // Maksimum penggunaan, opsional
            'used_count' => 0, // Jumlah penggunaan awal
            'start_date' => $this->faker->boolean(80) ? $this->faker->date() : null, // Tanggal mulai, opsional
            'end_date' => $this->faker->boolean(80) ? $this->faker->date() : null, // Tanggal berakhir, opsional
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
