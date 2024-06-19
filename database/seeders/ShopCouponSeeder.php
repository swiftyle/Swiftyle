<?php

namespace Database\Seeders;

use App\Models\ShopCoupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ShopCoupon::factory()->count(5)->create();
    }
}
