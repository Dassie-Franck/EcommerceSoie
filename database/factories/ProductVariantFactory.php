<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $colors = [
            ['name' => 'Or & Bordeaux',    'hex' => '#C9A84C'],
            ['name' => 'Bleu & Argent',    'hex' => '#4A6FA5'],
            ['name' => 'Terre & Crème',    'hex' => '#D85A30'],
            ['name' => 'Noir & Ocre',      'hex' => '#2C2C2A'],
            ['name' => 'Violet Royal',     'hex' => '#534AB7'],
            ['name' => 'Émeraude',         'hex' => '#2D6A4F'],
            ['name' => 'Orange Soleil',    'hex' => '#F4831F'],
            ['name' => 'Rose Sahara',      'hex' => '#D4537E'],
            ['name' => 'Rouge & Or',       'hex' => '#C0392B'],
            ['name' => 'Blanc Ivoire',     'hex' => '#FDFAF3'],
            ['name' => 'Bleu Nuit',        'hex' => '#1A237E'],
            ['name' => 'Multicolore',      'hex' => '#E8593C'],
            ['name' => 'Vert Tropical',    'hex' => '#2D9B4B'],
        ];

        $color     = fake()->randomElement($colors);
        $size      = fake()->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL', 'Unique']);
        $productId = Product::inRandomOrder()->first()?->id ?? 1;

        $sku = strtoupper(
            Str::random(4) . '-' .
            Str::slug($size) . '-' .
            fake()->unique()->numberBetween(1000, 9999)
        );

        return [
            'product_id'     => $productId,
            'size'           => $size,
            'color'          => $color['name'],
            'color_hex'      => $color['hex'],
            'stock_quantity' => fake()->numberBetween(0, 50),
            'price_modifier' => fake()->randomFloat(2, 0, 100),
            'sku'            => $sku,
        ];
    }

    public function inStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock_quantity' => fake()->numberBetween(5, 30),
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn(array $attributes) => [
            'stock_quantity' => 0,
        ]);
    }

    public function forProduct(int $productId): static
    {
        return $this->state(fn(array $attributes) => [
            'product_id' => $productId,
        ]);
    }
}
