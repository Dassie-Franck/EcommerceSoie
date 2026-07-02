<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $testUser = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name' => 'Client Test',
                'password' => bcrypt('password123'),
            ]
        );

        // Exemple 1: Commande expédiée avec tracking DHL
        Order::updateOrCreate(
            ['reference' => 'EC-SHIPPED-001'],
            [
                'order_number' => 'ORD-001',
                'user_id' => $testUser->id,
                'email' => 'client@test.com',
                'status' => 'shipped', // ✅ Valeur valide
                'tracking_number' => '8564385550',
                'shipping_carrier' => 'dhl',
                'subtotal' => 75000,
                'shipping_fee' => 5000,
                'shipping_cost' => 5000,
                'discount' => 0,
                'total' => 80000,
                'phone' => '+237 6 12 34 56 78',
                'address' => 'Rue Principale 123',
                'city' => 'Douala',
                'country' => 'Cameroun',
                'payment_method' => 'orange_money',
                'payment_status' => 'paid',
                'coupon_code' => null,
                'notes' => 'Colis expédié le 15/12/2024',
                'created_at' => '2024-12-10 10:00:00',
            ]
        );

        // Exemple 2: Commande livrée avec tracking DHL
        Order::updateOrCreate(
            ['reference' => 'EC-COMPLETED-002'], // Changé le nom
            [
                'order_number' => 'ORD-002',
                'user_id' => $testUser->id,
                'email' => 'client@test.com',
                'status' => 'completed', // ✅ Valeur valide (au lieu de delivered)
                'tracking_number' => '9876543210',
                'shipping_carrier' => 'dhl',
                'subtotal' => 125000,
                'shipping_fee' => 7500,
                'shipping_cost' => 7500,
                'discount' => 0,
                'total' => 132500,
                'phone' => '+237 6 98 76 54 32',
                'address' => 'Avenue de la Liberté 45',
                'city' => 'Yaoundé',
                'country' => 'Cameroun',
                'payment_method' => 'mtn_money',
                'payment_status' => 'paid',
                'coupon_code' => null,
                'notes' => 'Colis livré avec succès',
                'created_at' => '2024-12-05 14:30:00',
            ]
        );

        // Exemple 3: Commande en attente
        Order::updateOrCreate(
            ['reference' => 'EC-PENDING-003'],
            [
                'order_number' => 'ORD-003',
                'user_id' => $testUser->id,
                'email' => 'client@test.com',
                'status' => 'pending', // ✅ Valeur valide
                'tracking_number' => null,
                'shipping_carrier' => null,
                'subtotal' => 45000,
                'shipping_fee' => 3000,
                'shipping_cost' => 3000,
                'discount' => 0,
                'total' => 48000,
                'phone' => '+237 6 55 66 77 88',
                'address' => 'Quartier Bonapriso',
                'city' => 'Douala',
                'country' => 'Cameroun',
                'payment_method' => 'orange_money',
                'payment_status' => 'pending',
                'coupon_code' => null,
                'notes' => 'En attente de validation',
                'created_at' => '2024-12-18 09:15:00',
            ]
        );

        // Exemple 4: Commande en traitement
        Order::updateOrCreate(
            ['reference' => 'EC-PROCESSING-004'], // Changé le nom
            [
                'order_number' => 'ORD-004',
                'user_id' => $testUser->id,
                'email' => 'client@test.com',
                'status' => 'processing', // ✅ Valeur valide
                'tracking_number' => null,
                'shipping_carrier' => null,
                'subtotal' => 89000,
                'shipping_fee' => 6000,
                'shipping_cost' => 6000,
                'discount' => 0,
                'total' => 95000,
                'phone' => '+237 6 22 33 44 55',
                'address' => 'Rue des Étoiles 78',
                'city' => 'Garoua',
                'country' => 'Cameroun',
                'payment_method' => 'carte_bancaire',
                'payment_status' => 'paid',
                'coupon_code' => null,
                'notes' => 'En cours de préparation',
                'created_at' => '2024-12-17 16:45:00',
            ]
        );

        // Exemple 5: Commande avec Colissimo (France)
        Order::updateOrCreate(
            ['reference' => 'EC-COLISSIMO-005'],
            [
                'order_number' => 'ORD-005',
                'user_id' => $testUser->id,
                'email' => 'client@test.com',
                'status' => 'shipped', // ✅ Valeur valide
                'tracking_number' => '1Z999AA10123456784',
                'shipping_carrier' => 'colissimo',
                'subtotal' => 156000,
                'shipping_fee' => 12000,
                'shipping_cost' => 12000,
                'discount' => 0,
                'total' => 168000,
                'phone' => '+33 6 12 34 56 78',
                'address' => '15 Rue de Paris',
                'city' => 'Paris',
                'country' => 'France',
                'payment_method' => 'carte_bancaire',
                'payment_status' => 'paid',
                'coupon_code' => null,
                'notes' => 'Expédition internationale',
                'created_at' => '2024-12-15 11:20:00',
            ]
        );

        $this->command->info('✅ 5 commandes de test créées avec succès !');
        $this->command->info('');
        $this->command->info('📦 Commandes créées :');
        $this->command->info('   - EC-SHIPPED-001 (Expédiée - tracking: 8564385550)');
        $this->command->info('   - EC-COMPLETED-002 (Livrée - tracking: 9876543210)');
        $this->command->info('   - EC-PENDING-003 (En attente)');
        $this->command->info('   - EC-PROCESSING-004 (En traitement)');
        $this->command->info('   - EC-COLISSIMO-005 (Colissimo - tracking: 1Z999AA10123456784)');
        $this->command->info('');
        $this->command->info('🔗 Testez le suivi sur : http://localhost:8000/tracking');
        $this->command->info('   Email: client@test.com');
    }
}
