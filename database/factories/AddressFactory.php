<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\Country;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        $this->faker->addProvider(new CustomPhoneProvider($this->faker));
        return [
            'user_id' => $this->faker->numberBetween(1, 50),
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'type' => $this->faker->randomElement(['Home', 'Work', 'Other']),
            'primary' => $this->faker->boolean,
            'country' => $this->faker->randomElement(Country::getValues()),
            'province' => $this->faker->state,
            'city' => $this->faker->city,
            'district' => $this->faker->citySuffix,
            'street' => $this->faker->streetName,
            'house_number' => $this->faker->buildingNumber,
            'apartment_number' => $this->faker->optional()->numberBetween(1, 100),
            'postal_code' => $this->faker->postcode,
            'phone_number' => $this->faker->unique()->customPhoneNumber(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('now','+1 year'),
        ];
    }
}
