<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $titles = [
            'Absolument magnifique !',
            'Qualité exceptionnelle',
            'Très satisfaite de mon achat',
            'Conforme à la description',
            'Tissu de très bonne qualité',
            'Je recommande vivement',
            'Superbe robe, beaucoup de compliments',
            'Parfait pour mon mariage',
            'Taille un peu grande',
            'Couleurs moins vives qu\'en photo',
        ];

        $comments = [
            'J\'ai commandé cette robe pour le mariage de ma sœur et j\'ai eu énormément de compliments. Le tissu est d\'une qualité extraordinaire, très doux au toucher.',
            'La livraison a été rapide et l\'emballage soigné. Le produit est conforme à la description. Je suis très satisfaite et je recommande cette boutique.',
            'Superbe ensemble ! Les coutures sont impeccables et le tissu soie est vraiment de qualité. Je suis enchantée de mon achat.',
            'Belle pièce artisanale qui respire l\'authenticité africaine. La taille est un peu grande, penser à prendre une taille en dessous.',
            'Magnifique robe portée pour une cérémonie. Tout le monde me demandait d\'où elle venait ! Le tissu Kente est tissé à la main et ça se voit.',
            'Deuxième commande sur ce site et toujours aussi satisfaite. La qualité est constante et le service client est réactif.',
            'Le tissu est léger et fluide, parfait pour l\'été européen. La robe est bien coupée et valorise la silhouette.',
            'Les couleurs semblent un peu moins vives que sur les photos mais la qualité du tissu est bien là et la coupe est flatteuse.',
        ];

        return [
            'user_id'     => User::where('role', 'client')->inRandomOrder()->first()?->id ?? 1,
            'product_id'  => Product::inRandomOrder()->first()?->id ?? 1,
            'order_id'    => null,
            'rating'      => fake()->numberBetween(3, 5),
            'title'       => fake()->randomElement($titles),
            'comment'     => fake()->randomElement($comments),
            'is_approved' => fake()->boolean(70),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_approved' => true,
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_approved' => false,
        ]);
    }

    public function negative(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating' => fake()->numberBetween(1, 2),
        ]);
    }
}
