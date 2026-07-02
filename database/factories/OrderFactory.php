<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        // Liste des statuts possibles
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $status = $this->faker->randomElement($statuses);

        // Seuls les statuts 'shipped' et 'delivered' ont un numéro de suivi
        $hasTracking = in_array($status, ['shipped', 'delivered']);

        // Liste des numéros de suivi DHL factices
        $trackingNumbers = [
            '8564385550',
            '9876543210',
            '1234567890',
            '5551234567',
            '9998887776',
            '4445556667',
            '7778889990',
            '1112223334',
        ];

        // Liste des transporteurs
        $carriers = ['dhl', 'dhl_ecommerce', 'colissimo'];

        return [
            'reference' => 'EC-' . date('Ymd') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'user_id' => User::factory(), // Crée un utilisateur associé
            'email' => $this->faker->email(),
            'status' => $status,
            'tracking_number' => $hasTracking ? $this->faker->randomElement($trackingNumbers) : null,
            'shipping_carrier' => $hasTracking ? $this->faker->randomElement($carriers) : null,
            'subtotal' => $this->faker->numberBetween(10000, 200000),
            'shipping_fee' => $this->faker->numberBetween(1500, 10000),
            'total' => function($attributes) {
                return $attributes['subtotal'] + $attributes['shipping_fee'];
            },
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'country' => $this->faker->randomElement(['Cameroun', 'France', 'Canada', 'Belgique', 'Sénégal']),
            'payment_method' => $this->faker->randomElement(['orange_money', 'mtn_money', 'carte_bancaire']),
            'payment_status' => $this->faker->randomElement(['paid', 'pending', 'failed']),
            'notes' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }
}
