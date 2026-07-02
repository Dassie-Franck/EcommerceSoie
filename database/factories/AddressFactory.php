<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::where('role', 'client')->inRandomOrder()->first()?->id ?? 1,
            'full_name'   => fake()->name(),
            'phone'       => fake()->phoneNumber(),
            'street'      => fake()->streetAddress(),
            'city'        => fake()->city(),
            'postal_code' => fake()->postcode(),
            'country'     => fake()->randomElement([
                'Allemagne', 'France', 'Belgique',
                'Suisse', 'Pays-Bas', 'Cameroun', 'Sénégal',
            ]),
            'is_default'  => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_default' => true,
        ]);
    }

    public function german(): static
    {
        return $this->state(fn(array $attributes) => [
            'country' => 'Allemagne',
        ]);
    }
}
