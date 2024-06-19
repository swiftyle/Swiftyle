<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProductCreatedNotification;

class SendProductCreatedNotification implements ShouldQueue
{
    public function handle(ProductCreated $event)
    {
        $product = $event->product;

        // Dapatkan semua pengguna yang mengikuti toko tempat produk dibuat
        $followers = $product->shop->followers;

        // Kirim notifikasi ke setiap pengguna yang mengikuti toko
        foreach ($followers as $follower) {
            $follower->user->notify(new ProductCreatedNotification($product));
        }
    }
}
