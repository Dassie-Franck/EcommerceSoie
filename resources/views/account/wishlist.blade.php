@extends('layouts.app')

@section('title', 'Mes favoris - AfriSoie')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .wishlist-item {
        transition: all 0.3s ease;
        animation: fadeInUp 0.4s ease-out both;
    }
    .wishlist-item:nth-child(1) { animation-delay: 0.05s; }
    .wishlist-item:nth-child(2) { animation-delay: 0.1s; }
    .wishlist-item:nth-child(3) { animation-delay: 0.15s; }
    .wishlist-item:nth-child(4) { animation-delay: 0.2s; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .remove-wishlist-btn {
        transition: all 0.2s ease;
    }
    .remove-wishlist-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">

        {{-- En-tête --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-heart text-3xl text-[#9D8E1C]"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Mes favoris</h1>
                </div>
                <p class="text-gray-500 mt-1">
                    @if($wishlistItems->count() > 0)
                        {{ $wishlistItems->count() }} article(s) dans votre liste
                    @else
                        Votre liste de souhaits est vide
                    @endif
                </p>
            </div>
            <a href="{{ route('shop.catalogue') }}" class="text-sm text-gray-400 hover:text-[#9D8E1C] transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Continuer mes achats
            </a>
        </div>

        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    @php
                        $variant = $item->productVariant;
                        $product = $variant ? $variant->product : null;
                        $productName = $product ? $product->name : 'Produit indisponible';
                        $slug = $product ? $product->slug : '#';
                        $price = $variant ? $variant->final_price : 0;
                        $image = $product && $product->images->first() ? Storage::url($product->images->first()->path) : null;
                        $variantLabel = trim(($variant->size ?? '') . ' ' . ($variant->color ?? ''));
                    @endphp

                    <div class="wishlist-item bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group relative">

                        {{-- Bouton supprimer --}}
                        <form method="POST" action="{{ route('shop.wishlist.remove', $item->id) }}" class="absolute top-2 right-2 z-10">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-wishlist-btn w-8 h-8 rounded-full bg-white shadow-md flex items-center justify-center text-gray-400 hover:text-red-500 transition-all duration-200" onclick="return confirm('Retirer ce produit de vos favoris ?')">
                                <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                        </form>

                        {{-- Lien vers le produit --}}
                        <a href="{{ route('shop.product', $slug) }}" class="block">
                            {{-- Image --}}
                            <div class="relative overflow-hidden bg-gray-100 aspect-square">
                                @if($image)
                                    <img src="{{ $image }}" alt="{{ $productName }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-tshirt text-gray-300 text-4xl"></i>
                                    </div>
                                @endif
                            </div>

                            {{-- Infos produit --}}
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 text-sm line-clamp-2 mb-1 hover:text-[#9D8E1C] transition">
                                    {{ $productName }}
                                </h3>

                                @if($variantLabel)
                                    <p class="text-xs text-gray-500 mb-2">
                                        <i class="fas fa-palette text-[#9D8E1C] mr-1"></i>
                                        {{ $variantLabel }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-lg font-bold text-[#9D8E1C]">
                                        {{ number_format($price, 0, ',', ' ') }} €
                                    </span>

                                    {{-- Bouton ajouter au panier --}}
                                    <button onclick="addToCart({{ $variant->id }})" class="w-8 h-8 rounded-full bg-[#9D8E1C]/10 text-[#9D8E1C] hover:bg-[#9D8E1C] hover:text-white transition-all duration-200 flex items-center justify-center">
                                        <i class="fas fa-shopping-cart text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Liste vide --}}
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-gray-300 text-4xl"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium">Votre liste de favoris est vide</p>
                <p class="text-gray-400 text-sm mt-1">Explorez nos collections et ajoutez vos coups de cœur</p>
                <a href="{{ route('shop.catalogue') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl text-white transition-all duration-300" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                    <i class="fas fa-store"></i>
                    Découvrir les collections
                </a>
            </div>
        @endif
    </div>
</div>

<script>
function addToCart(productVariantId) {
    fetch('{{ route("shop.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_variant_id: productVariantId, quantity: 1 })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher une notification
            alert('Produit ajouté au panier !');
            // Mettre à jour le compteur du panier
            document.querySelector('.cart-count').textContent = data.cart_count;
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}
</script>
@endsection
