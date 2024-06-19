<?php

namespace Database\Seeders;

use App\Models\RefundRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefundRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RefundRequest::factory()->count(5)->create();
    }
}
