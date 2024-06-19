<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                UserSeeder::class, 
                // PasswordResetSeeder::class,
                // AddressSeeder::class,
                // MainCategorySeeder::class,
                // SubCategorySeeder::class,
                // ShopSeeder::class,
                // ProductSeeder::class,
                // ProductCategorySeeder::class,
                // GenreSeeder::class,
                // ProductGenreSeeder::class,
                // SizeSeeder::class,
                // ProductSizeSeeder::class,
                // StyleSeeder::class,
                // ProductStyleSeeder::class,
                // PromotionSeeder::class,
                // ProductPromotionSeeder::class,
                // PreferenceSeeder::class,
                // AppCouponSeeder::class,
                // ShopCouponSeeder::class,
                // WishlistSeeder::class,
                // WishlistItemSeeder::class,
                // CartSeeder::class,
                // CartItemSeeder::class,
                // ChatSeeder::class,
                // MessageSeeder::class,
                // PaymentSeeder::class,
                // CourierCategorySeeder::class,
                // CourierSeeder::class,
                // CheckoutSeeder::class,
                // ShippingSeeder::class,
                // OrderSeeder::class,
                // ReviewSeeder::class,
                // ComplaintSeeder::class,
                // RefundRequestSeeder::class,
                // RefundSeeder::class,
                // TransactionSeeder::class,
                // OrderHistorySeeder::class,
            ]
            );
    }
}
