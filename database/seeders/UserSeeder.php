<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            'remember_token' => Str::random(10),
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
            'remember_token' => Str::random(10),
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
            'remember_token' => Str::random(10),
            'updated_at' => null
        ]);

        User::create([
            'name' => 'Fathi',
            'username' => 'JustTyy',
            'email' => 'fathifatek@gmail.com',
            'password' => bcrypt('12345678'),
            'phone_number' => '(+62)81213570340',
            'gender' => 'Male',
            'role' => 'Admin',
            'remember_token' => Str::random(10),
            'updated_at' => null
        ]);

        User::create([
            'name' => 'Devi',
            'username' => 'Devi',
            'email' => 'devianggreyani1@gmail.com',
            'password' => bcrypt('12345678'),
            'phone_number' => '(+62)88212691489',
            'gender' => 'Female',
            'role' => 'Customer',
            'remember_token' => Str::random(10),
            'updated_at' => null
        ]);

        // User::factory()->count(46)->create();
    }
}
