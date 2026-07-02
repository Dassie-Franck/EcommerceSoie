<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $products = Product::all();

        foreach ($products as $product) {

            // 2 reviews max par produit
            $selectedClients = $clients->shuffle()->take(rand(1, 2));

            foreach ($selectedClients as $client) {

                // 🔥 GARANTIE ABSOLUE ANTI-DOUBLON
                if (!Review::where('user_id', $client->id)
                    ->where('product_id', $product->id)
                    ->exists()) {

                    Review::create(
                        array_merge(
                            Review::factory()->make()->toArray(),
                            [
                                'user_id' => $client->id,
                                'product_id' => $product->id,
                            ]
                        )
                    );
                }
            }
        }
    }
}
