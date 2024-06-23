<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;

class MidtransServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        Config::$serverKey = config('Mid-server-uHFIG_ZbKH9WX7L0sB-4h3_q');
        Config::$clientKey = config('Mid-client-8rhtTOzlQobNA0l8');
        Config::$isProduction = config('false');
        Config::$isSanitized = config('true');
        Config::$is3ds = config('true');
    }
}
