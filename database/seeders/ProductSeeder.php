<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    private array $sizes = ['S', 'M', 'L', 'XL'];

    public function run(): void
    {
        // ✅ Désactiver les foreign keys — une seule fois
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        ProductImage::truncate();
        ProductVariant::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $categories = Category::pluck('id', 'slug');

        $products = [

            // ════════════════════════════════════════════════════
            // DRESSES
            // ════════════════════════════════════════════════════
            [
                'category_slug' => 'dresses',
                'name'          => 'Robe Wax Bleue Africaine',
                'description'   => 'Magnifique robe en tissu wax bleu aux motifs africains traditionnels. Parfaite pour les cérémonies et soirées élégantes.',
                'base_price'    => 45.99,
                'compare_price' => 60.00,
                'fabric_type'   => 'Wax 100%',
                'origin'        => 'Afrique de l\'Ouest',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Bleu', 'hex' => '#1E40AF'],
                ],
                'images' => [
                    ['file' => 'Dresses/dresses-bleu-01.jpeg', 'primary' => true],
                    ['file' => 'Dresses/dresses-bleu-01.webp', 'primary' => false],
                ],
            ],
            [
                'category_slug' => 'dresses',
                'name'          => 'Robe Élégante Noire',
                'description'   => 'Robe noire sophistiquée en tissu africain premium. Un classique intemporel pour toutes les occasions.',
                'base_price'    => 50.00,
                'compare_price' => null,
                'fabric_type'   => 'Soie Africaine',
                'origin'        => 'Côte d\'Ivoire',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Noir', 'hex' => '#111827'],
                ],
                'images' => [
                    ['file' => 'Dresses/dresses-noir-01.webp', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'dresses',
                'name'          => 'Robe Wax Rouge Traditionnelle',
                'description'   => 'Robe rouge vibrante en wax traditionnel. Un symbole de fierté africaine et d\'élégance moderne.',
                'base_price'    => 45.00,
                'compare_price' => 55.00,
                'fabric_type'   => 'Wax',
                'origin'        => 'Ghana',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Rouge', 'hex' => '#DC2626'],
                ],
                'images' => [
                    ['file' => 'Dresses/dresses-rouge-01.webp', 'primary' => true],
                    ['file' => 'Dresses/dresses-rouge-02.webp', 'primary' => false],
                ],
            ],
            [
                'category_slug' => 'dresses',
                'name'          => 'Robe Vert Foncé Noire Premium',
                'description'   => 'Robe bicolore vert foncé et noir, une création originale alliant modernité et tradition africaine.',
                'base_price'    => 40.00,
                'compare_price' => 50.00,
                'fabric_type'   => 'Tissu Africain Premium',
                'origin'        => 'Sénégal',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Vert Foncé', 'hex' => '#064E3B'],
                    ['name' => 'Noir', 'hex' => '#111827'],
                ],
                'images' => [
                    ['file' => 'Dresses/dresses-vert-foncee-noir-01.jpeg', 'primary' => true],
                ],
            ],

            // ════════════════════════════════════════════════════
            // PANTS
            // ════════════════════════════════════════════════════
            [
                'category_slug' => 'pants',
                'name'          => 'Pantalon Élégant Blanc',
                'description'   => 'Pantalon blanc épuré en tissu africain de qualité. Idéal pour un look casual chic.',
                'base_price'    => 40.00,
                'compare_price' => null,
                'fabric_type'   => 'Lin Africain',
                'origin'        => 'Mali',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Blanc', 'hex' => '#F9FAFB'],
                ],
                'images' => [
                    ['file' => 'Pants/pantalon-blanc-01.jpg', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'pants',
                'name'          => 'Pantalon Classique Noir',
                'description'   => 'Pantalon noir intemporel en tissu africain tissé à la main. Confort et style garantis.',
                'base_price'    => 45.00,
                'compare_price' => 55.00,
                'fabric_type'   => 'Kente Tissé Main',
                'origin'        => 'Ghana',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Noir', 'hex' => '#111827'],
                ],
                'images' => [
                    ['file' => 'Pants/pantalon-noir-01.webp', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'pants',
                'name'          => 'Pantalon Wax Orange Vibrant',
                'description'   => 'Pantalon orange en wax africain, une pièce audacieuse pour se démarquer avec style.',
                'base_price'    => 45.99,
                'compare_price' => 60.00,
                'fabric_type'   => 'Wax',
                'origin'        => 'Côte d\'Ivoire',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Orange', 'hex' => '#EA580C'],
                ],
                'images' => [
                    ['file' => 'Pants/pantalon-orange-01.webp', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'pants',
                'name'          => 'Pantalon Rouge Collection Premium',
                'description'   => 'Collection premium de pantalons rouges en tissu africain sélectionné. Élégance et authenticité.',
                'base_price'    => 50.00,
                'compare_price' => 65.00,
                'fabric_type'   => 'Bazin Riche',
                'origin'        => 'Guinée',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Rouge', 'hex' => '#DC2626'],
                ],
                'images' => [
                    ['file' => 'Pants/pantalon-rouge-01.avif', 'primary' => true],
                    ['file' => 'Pants/pantalon-rouge-02.webp', 'primary' => false],
                    ['file' => 'Pants/pantalon-rouge-03.webp', 'primary' => false],
                    ['file' => 'Pants/pantalon-rouge-04.webp', 'primary' => false],
                ],
            ],
            [
                'category_slug' => 'pants',
                'name'          => 'Pantalon Vert Africain Nature',
                'description'   => 'Pantalon vert naturel inspiré des savanes africaines. Un must-have pour la saison.',
                'base_price'    => 40.00,
                'compare_price' => null,
                'fabric_type'   => 'Tissu Africain',
                'origin'        => 'Burkina Faso',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Vert', 'hex' => '#16A34A'],
                ],
                'images' => [
                    ['file' => 'Pants/pantalon-vert-01.jpg', 'primary' => true],
                ],
            ],

            // ════════════════════════════════════════════════════
            // MATCHING SETS
            // ════════════════════════════════════════════════════
            [
                'category_slug' => 'matchingsets',
                'name'          => 'Ensemble Coordonné Blanc Élégant',
                'description'   => 'Ensemble haut et bas blanc parfaitement coordonné. Un look chic et sophistiqué pour toutes occasions.',
                'base_price'    => 50.00,
                'compare_price' => 70.00,
                'fabric_type'   => 'Tissu Premium',
                'origin'        => 'Sénégal',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Blanc', 'hex' => '#F9FAFB'],
                ],
                'images' => [
                    ['file' => 'matchingSets/matching-sets-blanc-01.jpg', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'matchingsets',
                'name'          => 'Ensemble Wax Jaune Soleil',
                'description'   => 'Ensemble deux pièces jaune soleil en wax africain. Rayonnez avec style et authenticité.',
                'base_price'    => 45.99,
                'compare_price' => 60.00,
                'fabric_type'   => 'Wax Hollandais',
                'origin'        => 'Afrique de l\'Ouest',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Jaune', 'hex' => '#CA8A04'],
                ],
                'images' => [
                    ['file' => 'matchingSets/matching-sets-jaune-02.jpg', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'matchingsets',
                'name'          => 'Ensemble Noir Collection Luxe',
                'description'   => 'Collection luxe d\'ensembles noirs en tissu africain premium. L\'élégance à son summum.',
                'base_price'    => 50.00,
                'compare_price' => null,
                'fabric_type'   => 'Bazin Brodé',
                'origin'        => 'Mali',
                'is_featured'   => true,
                'colors'        => [
                    ['name' => 'Noir', 'hex' => '#111827'],
                ],
                'images' => [
                    ['file' => 'matchingSets/matching-sets-noir-01.jpg', 'primary' => true],
                    ['file' => 'matchingSets/matching-sets-noir-02.jpg', 'primary' => false],
                    ['file' => 'matchingSets/matching-sets-noir-03.jpg', 'primary' => false],
                ],
            ],
            [
                'category_slug' => 'matchingsets',
                'name'          => 'Ensemble Wax Rouge Traditionnel',
                'description'   => 'Ensemble rouge en wax traditionnel africain. Parfait pour les cérémonies et événements spéciaux.',
                'base_price'    => 45.00,
                'compare_price' => 58.00,
                'fabric_type'   => 'Wax',
                'origin'        => 'Ghana',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Rouge', 'hex' => '#DC2626'],
                ],
                'images' => [
                    ['file' => 'matchingSets/matching-sets-rouge-01.jpg', 'primary' => true],
                ],
            ],
            [
                'category_slug' => 'matchingsets',
                'name'          => 'Ensemble Bicolore Noir et Blanc',
                'description'   => 'Ensemble deux pièces noir et blanc, une combinaison intemporelle revisitée à l\'africaine.',
                'base_price'    => 45.99,
                'compare_price' => 60.00,
                'fabric_type'   => 'Tissu Africain Mixte',
                'origin'        => 'Côte d\'Ivoire',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Noir', 'hex' => '#111827'],
                    ['name' => 'Blanc', 'hex' => '#F9FAFB'],
                ],
                'images' => [
                    ['file' => 'matchingSets/set-noir-blanc-01.jpeg', 'primary' => true],
                ],
            ],

            // ════════════════════════════════════════════════════
            // SHORTS
            // ════════════════════════════════════════════════════
            [
                'category_slug' => 'shorts',
                'name'          => 'Short Wax Bleu Moderne',
                'description'   => 'Short bleu en wax africain pour un look décontracté et stylé. Parfait pour l\'été.',
                'base_price'    => 25.00,
                'compare_price' => 35.00,
                'fabric_type'   => 'Wax',
                'origin'        => 'Afrique de l\'Ouest',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Bleu', 'hex' => '#1D4ED8'],
                ],
                'images' => [
                    ['file' => 'shorts/short-bleu-02.jpg', 'primary' => true],
                    ['file' => 'shorts/shorts-bleu-01.jpg', 'primary' => false],
                ],
            ],
            [
                'category_slug' => 'shorts',
                'name'          => 'Short Gradient Multicolore',
                'description'   => 'Short aux couleurs dégradées uniques, une pièce artistique inspirée des traditions africaines.',
                'base_price'    => 20.00,
                'compare_price' => 30.00,
                'fabric_type'   => 'Tissu Teint à la Main',
                'origin'        => 'Sénégal',
                'is_featured'   => false,
                'colors'        => [
                    ['name' => 'Multicolore', 'hex' => '#7C3AED'],
                ],
                'images' => [
                    ['file' => 'shorts/short-gradiant-01.jpg', 'primary' => true],
                ],
            ],
        ];

        // ── INSERTION ─────────────────────────────────────────────
        foreach ($products as $data) {
            $categoryId = $categories[$data['category_slug']] ?? null;

            if (! $categoryId) {
                $this->command->warn("⚠️  Catégorie '{$data['category_slug']}' introuvable — produit ignoré.");
                continue;
            }

            // Slug unique
            $slug         = Str::slug($data['name']);
            $originalSlug = $slug;
            $count        = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }

            $product = Product::create([
                'category_id'       => $categoryId,
                'name'              => $data['name'],
                'slug'              => $slug,
                'description'       => $data['description'],
                'base_price'        => $data['base_price'],
                'compare_price'     => $data['compare_price'],
                'fabric_type'       => $data['fabric_type'],
                'origin'            => $data['origin'],
                'is_active'         => true,
                'is_featured'       => $data['is_featured'],
                'care_instructions' => 'Lavage à la main recommandé. Séchage à l\'air libre. Ne pas repasser à haute température.',
            ]);

            // Images
            foreach ($data['images'] as $index => $imageData) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'path'       => 'products/' . $imageData['file'],
                    'alt'        => $data['name'],
                    'is_primary' => $imageData['primary'],
                    'sort_order' => $index + 1,
                ]);
            }

            // Variantes (tailles × couleurs)
            foreach ($data['colors'] as $color) {
                foreach ($this->sizes as $size) {
                    $sku = strtoupper(
                        substr($data['category_slug'], 0, 3) . '-' .
                        substr(Str::slug($color['name']), 0, 3) . '-' .
                        $size . '-' .
                        rand(1000, 9999)
                    );

                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'size'           => $size,
                        'color'          => $color['name'],
                        'color_hex'      => $color['hex'],
                        'sku'            => $sku,
                        'price_modifier' => 0.00,
                        'stock_quantity' => rand(5, 30),
                        'is_active'      => true,
                    ]);
                }
            }

            $this->command->info("✅ {$data['name']}");
        }

        $this->command->info('');
        $this->command->info('🎉 ' . count($products) . ' produits créés !');
        $this->command->info('📦 ' . ProductVariant::count() . ' variantes créées !');
        $this->command->info('🖼️  ' . ProductImage::count() . ' images enregistrées !');
    }
}
