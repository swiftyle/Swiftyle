<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Fajri Yanuar',
            'username' => 'fajriyanuar',
            'email' => 'fajriyanuar1@gmail.com',
            'password' => bcrypt('12345678'),
            'phone_number' => '(+62)85217861296',
            'gender' => 'Male',
            'role' => 'Admin',
            'updated_at' => null
        ]);
        
        User::create([
            'name' => 'Dzaky Jaisy',
            'username' => 'MiNerVa',
            'email' => 'alqorneydzaki03@gmail.com',
            'password' => bcrypt('testestes'),
            'phone_number' => '(+62)85781509636',
            'gender' => 'Male',
            'role' => 'Admin',
            'updated_at' => null
        ]);
        
        User::create([
            'name' => 'Indah',
            'username' => 'indahnir',
            'email' => 'nirmalaindah616@gmail.com',
            'password' => bcrypt('testestes'),
            'phone_number' => '(+62)83814720164',
            'gender' => 'Female',
            'role' => 'Seller',
            'updated_at' => null
        ]);

        User::factory()->count(47)->create();
    }
}
