<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new CustomPhoneProvider($this->faker));

        $status = $this->faker->randomElement(['Active', 'Inactive']);
        $deletedAt = $status === 'Inactive' ? $this->faker->dateTimeBetween('now','+1 year' ) : null;
        $updatedAt = $status === 'Active' ? $this->faker->dateTimeBetween('now', '+6 months') : null;
        
        if ($status === 'Inactive' && $updatedAt && $deletedAt && $deletedAt < $updatedAt) {
            $deletedAt = $updatedAt;
        }

        return [
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt($this->faker->randomNumber),
            'phone_number' => $this->faker->unique()->customPhoneNumber(),
            'phone_verified' => $this->faker->randomElement(['Yes', 'No']),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'role' => $this->faker->randomElement(['Admin', 'Customer', 'Seller']),
            'avatar' => 'http://localhost:8000/assets/images/dashboard/1.png',
            'status' => $status,
            'provider' => $this->faker->randomElement(['google', 'facebook', 'twitter']),
            'provider_id' => $this->faker->randomNumber(),
            'provider_token' => $this->faker->uuid,
            'remember_token' => Str::random(10),
            'deleted_at' => $deletedAt,
            'created_at' => now(),
            'updated_at' => $updatedAt
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
