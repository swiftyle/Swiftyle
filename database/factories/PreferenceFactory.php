<?php

namespace Database\Factories;

use App\Models\Preference;
use App\Models\User;
use App\Models\Style;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), // Membuat atau mengambil ID dari instance User
            'style_id' => Style::factory(), // Membuat atau mengambil ID dari instance Style
            'genre_id' => Genre::factory(), // Membuat atau mengambil ID dari instance Genre
            'created_at' => now(), // Timestamp saat ini
            'updated_at' => now(), // Timestamp saat ini
            'deleted_at' => null, // Tidak ada soft delete awalnya
        ];
    }
}
