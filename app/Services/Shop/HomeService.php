<?php

namespace App\Services\Shop;

use App\Contracts\Shop\HomeServiceInterface;
use App\Models\Product;

class HomeService implements HomeServiceInterface
{
    public function getHomeData(): array
    {
        $tab = request()->get('tab', 'for-you');

        $latestProducts = Product::with(['images' => fn($q) => $q->where('is_primary', true),
                                         'variants' => fn($q) => $q->where('is_active', true)
                                                                    ->select('product_id','color','color_hex')
                                                                    ->distinct()])
            ->where('is_active', true)
            ->when($tab === 'clothing',    fn($q) => $q->whereNotNull('compare_price'))
            ->when($tab === 'eclatdeals',  fn($q) => $q->latest())
            ->when($tab === 'dresses', fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'dresses')))
            ->when($tab === 'pants',   fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'pants')))
            ->when($tab === 'kimonos',    fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'kimonos')))
            ->when($tab === 'sets',   fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'matchingsets')))
            ->when($tab === 'shorts',   fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', 'shorts')))
            
            ->latest()
            ->take(8)
            ->get();

        return compact('latestProducts', 'tab');
    }
}
