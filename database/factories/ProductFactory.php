<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $prefixes = ['Robe', 'Ensemble', 'Tunique', 'Blouse', 'Jupe', 'Boubou', 'Veste'];
        $styles   = [
            'Kente Royal', 'Bogolan Sahel', 'Ankara Prestige',
            'Dashiki Bohème', 'Bazin Festif', 'Wax Fleurie',
            'Adinkra Élégante', 'Ndop Traditionnel', 'Mudcloth Contemporaine',
        ];
        $fabrics  = [
            'Soie Kente', 'Soie Bogolan', 'Soie Ankara', 'Soie Dashiki',
            'Bazin Riche Soie', 'Soie Wax', 'Pure Soie Adinkra',
            'Soie Ndop', 'Soie fluide imprimée',
        ];
        $origins  = [
            'Ghana', 'Nigeria', 'Sénégal', 'Mali', 'Côte d\'Ivoire',
            'Cameroun', 'Guinée', 'Éthiopie', 'Tanzanie',
        ];
        $descriptions = [
            'Confectionnée à la main par des artisans africains, cette pièce unique allie tradition et modernité. Les motifs géométriques caractéristiques racontent l\'histoire et la culture d\'un peuple.',
            'Un vêtement d\'exception réalisé en tissu soie de première qualité. Les couleurs vives et les motifs authentiques en font une pièce incontournable pour toutes vos occasions.',
            'L\'élégance africaine à son summum. Ce tissu soie tissé selon des techniques ancestrales offre une douceur incomparable et un tombé parfait pour une silhouette valorisée.',
            'Inspirée des grandes traditions vestimentaires d\'Afrique de l\'Ouest, cette création marie harmonie et modernité dans un équilibre parfait.',
            'Chaque fil de ce tissu soie a été sélectionné avec soin pour garantir une qualité supérieure. Une pièce luxueuse qui se porte avec aisance pour le quotidien comme pour les cérémonies.',
        ];

        $name = fake()->randomElement($prefixes) . ' ' . fake()->randomElement($styles);

        return [
            'name'        => $name,
            'slug'        => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'description' => fake()->randomElement($descriptions),
            'base_price'  => fake()->randomFloat(2, 59, 320),
            'fabric_type' => fake()->randomElement($fabrics),
            'origin'      => fake()->randomElement($origins),
            'is_active'   => true,
            'is_featured' => fake()->boolean(30),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function forCategory(int $categoryId): static
    {
        return $this->state(fn(array $attributes) => [
            'category_id' => $categoryId,
        ]);
    }
}
