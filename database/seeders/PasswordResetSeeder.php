<?php

namespace Database\Seeders;

use App\Models\PasswordReset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PasswordResetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PasswordReset::factory()->count(5)->create();
    }
}
