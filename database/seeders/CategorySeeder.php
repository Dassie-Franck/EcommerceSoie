<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Désactiver les foreign keys avant truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Category::truncate();

        // ✅ Réactiver après
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = [
            [
                'name'        => 'Clothing',
                'slug'        => 'clothing',
                'description' => 'Notre collection complète de vêtements africains en soie.',
                'image'       => null,
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Dresses',
                'slug'        => 'dresses',
                'description' => 'Robes africaines élégantes pour toutes les occasions.',
                'image'       => 'categories/dresses-categories.webp',
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Pants',
                'slug'        => 'pants',
                'description' => 'Pantalons africains modernes et confortables.',
                'image'       => 'categories/pantalon-blanc-categories-01.jpeg',
                'is_active'   => true,
                'sort_order'  => 3,
            ],
            [
                'name'        => 'Matching Sets',
                'slug'        => 'matchingsets',
                'description' => 'Ensembles coordonnés pour un look parfait.',
                'image'       => 'categories/set-rouge-01-categories.jpeg',
                'is_active'   => true,
                'sort_order'  => 4,
            ],
            [
                'name'        => 'Shorts',
                'slug'        => 'shorts',
                'description' => 'Shorts africains stylés pour un look décontracté.',
                'image'       => null,
                'is_active'   => true,
                'sort_order'  => 5,
            ],
            [
                'name'        => 'Kimonos',
                'slug'        => 'kimonos',
                'description' => 'Kimonos africains en soie pour un style unique.',
                'image'       => null,
                'is_active'   => true,
                'sort_order'  => 6,
            ],
            [
                'name'        => 'Eclat Deals',
                'slug'        => 'eclatsdeals',
                'description' => 'Nos meilleures offres et promotions exclusives.',
                'image'       => null,
                'is_active'   => true,
                'sort_order'  => 7,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ ' . count($categories) . ' catégories créées !');
    }
}
