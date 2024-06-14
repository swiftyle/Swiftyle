<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Shop;
use App\Models\ShopCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShopCouponFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShopCoupon::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $shop = Shop::inRandomOrder()->first();

        return [
            'shop_id' => Shop::inRandomOrder()->first()->id,
            'name' => $this->faker->name(),
            'code' => strtoupper($this->faker->unique()->lexify('COUPON-??????')), // Kode kupon unik dengan pola
            'type' => $this->faker->randomElement(['percentage_discount', 'fixed_discount']), // Jenis diskon acak
            'discount_amount' => $this->faker->randomFloat(2, 5, 50), // Jumlah diskon antara 5 dan 50
            'max_uses' => $this->faker->numberBetween(1, 100), // Maksimum penggunaan, opsional
            'used_count' => 0, // Jumlah penggunaan awal
            'start_date' => $this->faker->date(), // Tanggal mulai, opsional
            'end_date' => $this->faker->date(), // Tanggal berakhir, opsional
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
