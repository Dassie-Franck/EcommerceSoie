<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        $seed = fake()->unique()->numberBetween(1, 9999);

        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
            'path' => 'https://picsum.photos/...',

'alt' => 'Photo produit AfriSoie',
            'is_primary' => false,
            'sort_order' => fake()->numberBetween(2, 5),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_primary' => true,
            'sort_order' => 1,
        ]);
    }

    public function forProduct(int $productId, string $slug = ''): static
    {
        $seed = $slug ?: fake()->word();
        return $this->state(fn(array $attributes) => [
            'product_id' => $productId,
            'path' => "https://picsum.photos/seed/{$seed}/800/1000",
        ]);
    }
}
