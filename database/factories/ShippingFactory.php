<?php

namespace Database\Factories;

use App\Models\Shipping;
use App\Models\Checkout;
use App\Models\Address;
use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipping::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $checkout = Checkout::factory()->create();
        $address = Address::find($checkout->id_address);
        $courier = Courier::find($checkout->courier_id);

        $shippingAddress = $address
            ? $this->formatAddress($address)
            : $this->faker->address;

        $courierName = $courier
            ? $courier->name
            : $this->faker->company;

        return [
            'checkout_id' => $checkout->id,
            'shipping_address' => $shippingAddress,
            'courier_name' => $courierName,
            'tracking_number' => $this->faker->uuid,
            'shipped_date' => $this->faker->date(),
            'shipping_method' => $this->faker->randomElement(['car', 'ship', 'plane']),
            'shipping_cost' => $this->faker->randomFloat(2, 10, 100),
            'shipping_status' => $this->faker->randomElement(['pending', 'shipped', 'delivered', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['cod', 'paid']),
            'estimated_delivery_date' => $this->faker->date(),
        ];
    }

    /**
     * Format the address from the Address model.
     *
     * @param Address $address
     * @return string
     */
    protected function formatAddress(Address $address)
    {
        return "{$address->firstname} {$address->lastname}, "
            . "{$address->type}, "
            . "{$address->street}, "
            . "{$address->house_number}, "
            . "{$address->apartment_number}, "
            . "{$address->district}, "
            . "{$address->city}, "
            . "{$address->province}, "
            . "{$address->postal_code}, "
            . "{$address->country}, "
            . "Phone: {$address->phone_number}";
    }
}

