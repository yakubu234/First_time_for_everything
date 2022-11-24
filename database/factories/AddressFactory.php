<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Provider\Address;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $max = User::count();
        $min = ($max > 200) ? (($max -200) + 1) : 1;
        return [
            'user_id' => fake()->unique(true)->numberBetween($min, $max),
            'address' => fake()->StreetAddress(),
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'zip' => Address::postcode()
        ];
    }
}
