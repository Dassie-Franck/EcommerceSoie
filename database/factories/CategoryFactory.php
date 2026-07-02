<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Robes longues', 'Robes de soirée', 'Robes courtes',
            'Ensembles 2 pièces', 'Ensembles 3 pièces',
            'Blouses & Tops', 'Tuniques & Boubous',
            'Jupes longues', 'Jupes mi-longues',
            'Foulards & Turbans', 'Sacs & Accessoires',
            'Vestes & Manteaux', 'Pantalons & Shorts',
        ]);

        return [
            'name'      => $name,
            'slug'      => Str::slug($name),
            'parent_id' => null,
            'is_active' => true,
        ];
    }

    public function child(int $parentId): static
    {
        return $this->state(fn(array $attributes) => [
            'parent_id' => $parentId,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
