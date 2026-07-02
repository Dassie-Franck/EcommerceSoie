<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name'       => 'Allemagne',
                'countries'  => json_encode(['DE']),
                'price'      => 4.90,
                'free_above' => 80.00,
                'days_min'   => 2,
                'days_max'   => 4,
                'is_active'  => true,
            ],
            [
                'name'       => 'Europe de l\'Ouest',
                'countries'  => json_encode(['FR', 'BE', 'NL', 'LU', 'AT', 'CH']),
                'price'      => 8.90,
                'free_above' => 120.00,
                'days_min'   => 3,
                'days_max'   => 6,
                'is_active'  => true,
            ],
            [
                'name'       => 'Europe du Sud',
                'countries'  => json_encode(['ES', 'IT', 'PT', 'GR']),
                'price'      => 10.90,
                'free_above' => 150.00,
                'days_min'   => 4,
                'days_max'   => 7,
                'is_active'  => true,
            ],
            [
                'name'       => 'Europe du Nord',
                'countries'  => json_encode(['SE', 'NO', 'DK', 'FI']),
                'price'      => 12.90,
                'free_above' => 150.00,
                'days_min'   => 4,
                'days_max'   => 8,
                'is_active'  => true,
            ],
            [
                'name'       => 'Afrique de l\'Ouest',
                'countries'  => json_encode(['CM', 'SN', 'CI', 'ML', 'GH', 'GN', 'TG', 'BJ']),
                'price'      => 18.90,
                'free_above' => 200.00,
                'days_min'   => 7,
                'days_max'   => 14,
                'is_active'  => true,
            ],
            [
                'name'       => 'Afrique Centrale',
                'countries'  => json_encode(['CG', 'CD', 'GA', 'CF', 'TD']),
                'price'      => 22.90,
                'free_above' => 250.00,
                'days_min'   => 10,
                'days_max'   => 18,
                'is_active'  => true,
            ],
            [
                'name'       => 'Reste du Monde',
                'countries'  => json_encode(['*']),
                'price'      => 29.90,
                'free_above' => 300.00,
                'days_min'   => 10,
                'days_max'   => 21,
                'is_active'  => true,
            ],
        ];

        foreach ($zones as $zone) {
            ShippingZone::create($zone);
        }
    }
}
