@extends('layouts.app')
@section('title', 'Catalogue — AfriSoie')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
    }
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
    }
    .price-tag {
        color: #9D8E1C;
        font-weight: 700;
    }
    .old-price {
        text-decoration: line-through !important;
        color: #9CA3AF !important;
        font-size: 0.75rem;
    }
    /* Badge réduction - ROUGE */
    .badge-discount {
        background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%) !important;
        color: white !important;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    /* Badge vedette - VERT */
    .badge-featured {
        background: linear-gradient(135deg, #22C55E 0%, #15803D 100%) !important;
        color: white !important;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    /* Badge nouveauté - BLEU */
    .badge-new {
        background: linear-gradient(135deg, #3B82F6 0%, #1E3A8A 100%) !important;
        color: white !important;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.25rem 0.6rem;
        border-radius: 20px;
    }
    .color-swatch {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 0.25rem;
        border: 1px solid #e5e7eb;
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .color-swatch:hover {
        transform: scale(1.2);
    }
    .filter-sidebar {
        transition: all 0.3s ease;
    }
    .filter-group {
        border-bottom: 1px solid rgba(0,0,0,0.08);
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .filter-group:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .product-card {
        animation: fadeInUp 0.4s ease-out both;
    }
    .product-card:nth-child(n+1) { animation-delay: 0.05s; }
    .product-card:nth-child(n+5) { animation-delay: 0.1s; }
    .product-card:nth-child(n+9) { animation-delay: 0.15s; }
    .product-card:nth-child(n+13) { animation-delay: 0.2s; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center gap-3">
                <i class="fas fa-check-circle text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- En-tête --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-store text-[#9D8E1C]"></i>
                        Notre Catalogue
                    </h1>
                    <p class="text-gray-500 mt-1">
                        <i class="fas fa-magic mr-1 text-xs"></i>
                        Découvrez l'élégance de la soie africaine
                    </p>
                </div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-chart-line"></i>
                    <span>{{ $products->total() }} produit(s) disponible(s)</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- FILTRES --}}
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 sticky top-24 overflow-hidden">
                    <div class="p-5 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-sliders-h text-[#9D8E1C]"></i>
                                <h2 class="font-semibold text-gray-900">Filtres</h2>
                            </div>
                            @if(request()->hasAny(['q', 'category', 'min_price', 'max_price', 'sort']))
                                <a href="{{ route('shop.catalogue') }}" class="text-xs text-gray-400 hover:text-[#9D8E1C] transition flex items-center gap-1">
                                    <i class="fas fa-undo-alt"></i> Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>

                    <form method="GET" action="{{ route('shop.catalogue') }}" class="p-5 filter-sidebar">
                        {{-- Recherche --}}
                        <div class="filter-group">
                            <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">
                                <i class="fas fa-search text-[#9D8E1C]"></i> Recherche
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Nom, description..."
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:border-[#9D8E1C] focus:ring-2 focus:ring-[#9D8E1C]/20 outline-none transition text-sm">
                        </div>

                        {{-- Catégorie --}}
                        <div class="filter-group">
                            <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">
                                <i class="fas fa-tag text-[#9D8E1C]"></i> Catégorie
                            </label>
                            <select name="category" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:border-[#9D8E1C] outline-none transition text-sm bg-white">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Prix --}}
                        <div class="filter-group">
                            <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">
                                <i class="fas fa-euro-sign text-[#9D8E1C]"></i> Prix
                            </label>
                            <div class="flex gap-3">
                                <div class="flex-1 relative">
                                    <i class="fas fa-euro-sign absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                           placeholder="Min" class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-xl outline-none text-sm">
                                </div>
                                <div class="flex-1 relative">
                                    <i class="fas fa-euro-sign absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                           placeholder="Max" class="w-full pl-7 pr-3 py-2 border border-gray-200 rounded-xl outline-none text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Tri --}}
                        <div class="filter-group">
                            <label class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-500 mb-3">
                                <i class="fas fa-sort-amount-down-alt text-[#9D8E1C]"></i> Trier par
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sort" value="latest" {{ request('sort', 'latest') == 'latest' ? 'checked' : '' }} class="accent-[#9D8E1C]" onchange="this.form.submit()">
                                    <span class="text-sm text-gray-700">Plus récents</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sort" value="price_asc" {{ request('sort') == 'price_asc' ? 'checked' : '' }} class="accent-[#9D8E1C]" onchange="this.form.submit()">
                                    <span class="text-sm text-gray-700">Prix croissant</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="sort" value="price_desc" {{ request('sort') == 'price_desc' ? 'checked' : '' }} class="accent-[#9D8E1C]" onchange="this.form.submit()">
                                    <span class="text-sm text-gray-700">Prix décroissant</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="w-full mt-4 py-2.5 rounded-xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                            <i class="fas fa-check"></i> Appliquer
                        </button>
                    </form>
                </div>
            </aside>

            {{-- GRILLE PRODUITS --}}
            <div class="flex-1">
                @if($products->count())
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        @foreach($products as $product)
                            @php
                                $primaryImage = $product->images->first();

                                // Calcul de la réduction
                                $hasDiscount = false;
                                $discount = 0;
                                $originalPrice = null;
                                $finalPrice = $product->base_price;

                                if($product->compare_price && $product->compare_price > 0 && $product->base_price < $product->compare_price) {
                                    $hasDiscount = true;
                                    $originalPrice = $product->compare_price;
                                    $discount = round((1 - $product->base_price / $product->compare_price) * 100);
                                }

                                // Récupérer les couleurs uniques des variantes
                                $uniqueColors = collect();
                                if($product->variants && $product->variants->count() > 0) {
                                    $uniqueColors = $product->variants->groupBy('color')->keys()->take(4);
                                }
                            @endphp

                            <a href="{{ route('shop.product', $product->slug) }}" class="product-card group bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-[#9D8E1C]/30 block">

                                {{-- Image --}}
                                <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
                                    @if($primaryImage)
                                        <img src="{{ Storage::url($primaryImage->path) }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas fa-image text-gray-300 text-5xl"></i>
                                        </div>
                                    @endif

                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 flex flex-col gap-1">
    @if($hasDiscount)
        <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md flex items-center gap-1">
            <i class="fas fa-tag text-[10px]"></i>
            -{{ $discount }}%
        </span>
    @endif
</div>

<div class="absolute top-3 right-3 flex flex-col gap-1">
    @if($product->is_featured)
        <span class="bg-green-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md flex items-center gap-1">
            <i class="fas fa-star text-[10px]"></i>
            Vedette
        </span>
    @endif
    @if($product->is_new)
        <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-md flex items-center gap-1">
            <i class="fas fa-star text-[10px]"></i>
            Nouveau
        </span>
    @endif
</div>



                                    {{-- Overlay au survol --}}
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <div class="bg-white rounded-full p-2 shadow-lg transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                            <i class="fas fa-eye text-[#9D8E1C]"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- Infos produit --}}
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">
                                            <i class="fas fa-folder-open mr-1"></i>{{ $product->category->name ?? 'Collection' }}
                                        </p>
                                        {{-- Swatches couleurs --}}
                                        @if($uniqueColors->count() > 0)
                                            <div class="flex items-center gap-0.5">
                                                @foreach($uniqueColors as $colorName)
                                                    @php
                                                        $colorHex = '#9D8E1C';
                                                        if($product->variants) {
                                                            $variant = $product->variants->where('color', $colorName)->first();
                                                            if($variant && $variant->color_hex) {
                                                                $colorHex = $variant->color_hex;
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="color-swatch" style="background-color: {{ $colorHex }};" title="{{ $colorName }}"></span>
                                                @endforeach
                                                @if($product->variants && $product->variants->groupBy('color')->count() > 4)
                                                    <span class="text-xs text-gray-400 ml-1">
                                                        +{{ $product->variants->groupBy('color')->count() - 4 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <h3 class="font-semibold text-gray-800 text-sm mt-1 line-clamp-2 group-hover:text-[#9D8E1C] transition">
                                        {{ $product->name }}
                                    </h3>

                                    {{-- PRIX --}}
                                    <div class="flex items-baseline gap-2 mt-2 flex-wrap">
                                        @if($hasDiscount)
                                         <span class="price-tag text-base font-bold">
                                                {{ number_format($finalPrice, 0, ',', ' ') }} €
                                            </span>
                                            <span class="old-price font-bold line-through ">
                                                {{ number_format($originalPrice, 0, ',', ' ') }} €
                                            </span>

                                        @else
                                            <span class="price-tag text-base font-bold">
                                                {{ number_format($finalPrice, 0, ',', ' ') }} €
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Type de tissu --}}
                                    <div class="flex items-center gap-3 mt-3 pt-2 border-t border-gray-50">
                                        <div class="flex items-center gap-1 text-xs text-gray-400">
                                            <i class="fas fa-tshirt"></i>
                                            <span>{{ $product->fabric_type ?? 'Tissu premium' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $products->appends(request()->query())->links() }}
                    </div>

                @else
                    <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-box-open text-gray-300 text-4xl"></i>
                        </div>
                        <p class="text-gray-500 text-lg font-medium">Aucun produit trouvé</p>
                        <p class="text-gray-400 text-sm mt-1">Essayez de modifier vos filtres de recherche</p>
                        <a href="{{ route('shop.catalogue') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-2.5 rounded-xl text-white transition-all" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                            <i class="fas fa-sync-alt"></i> Voir tous les produits
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit du formulaire quand on change de tri
    document.querySelectorAll('input[name="sort"]').forEach(radio => {
        radio.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endsection
