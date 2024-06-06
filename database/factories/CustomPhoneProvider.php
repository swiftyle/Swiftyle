<?php

namespace Database\Factories;

use Faker\Provider\Base as BaseProvider;

class CustomPhoneProvider extends BaseProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public static function customPhoneNumber()
    {
        return '(+62)8' . static::numerify('##########');
    }
}
