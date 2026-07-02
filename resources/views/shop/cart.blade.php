@extends('layouts.app')
@section('title', 'Mon panier — AfriSoie')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .cart-item {
        transition: all 0.3s ease;
        animation: fadeInUp 0.4s ease-out both;
    }
    .cart-item:nth-child(1) { animation-delay: 0.05s; }
    .cart-item:nth-child(2) { animation-delay: 0.1s; }
    .cart-item:nth-child(3) { animation-delay: 0.15s; }
    .cart-item:nth-child(4) { animation-delay: 0.2s; }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .quantity-btn {
        transition: all 0.2s ease;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: white;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .quantity-btn:hover {
        background: #9D8E1C;
        border-color: #9D8E1C;
        color: white;
    }
    .quantity-input {
        width: 50px;
        text-align: center;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 0;
        font-weight: 500;
    }
    .checkout-btn {
        transition: all 0.3s ease;
    }
    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(157, 142, 28, 0.3);
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-5xl">

        {{-- En-tête --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-shopping-cart text-3xl text-[#9D8E1C]"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Mon panier</h1>
                </div>
                <p class="text-gray-500 mt-1">Retrouvez vos articles sélectionnés</p>
            </div>
            <a href="{{ route('shop.catalogue') }}" class="text-sm text-gray-400 hover:text-[#9D8E1C] transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Continuer mes achats
            </a>
        </div>

        @if($cart && $cart->items->count())
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Liste des articles --}}
                <div class="lg:col-span-2 space-y-3">
                    @foreach($cart->items as $item)
                        @php
                            $product = optional($item->productVariant)->product;
                            $variant = $item->productVariant;
                            $productName = $product->name ?? 'Produit indisponible';
                            $variantLabel = trim(($variant->size ?? '') . ' ' . ($variant->color ?? ''));
                            $price = $variant ? $variant->finalPrice() : 0;
                            $total = $item->quantity * $price;
                            $image = $product && $product->images->first() ? Storage::url($product->images->first()->path) : null;
                        @endphp

                        <div class="cart-item bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                            <div class="flex flex-col sm:flex-row gap-4">

                                {{-- Image --}}
                                <div class="flex-shrink-0">
                                    @if($image)
                                        <img src="{{ $image }}" alt="{{ $productName }}" class="w-24 h-24 rounded-xl object-cover border border-gray-100">
                                    @else
                                        <div class="w-24 h-24 rounded-xl bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-tshirt text-gray-300 text-3xl"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Infos produit --}}
                                <div class="flex-1">
                                    <div class="flex flex-wrap justify-between gap-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-800 hover:text-[#9D8E1C] transition">
                                                <a href="{{ $product ? route('shop.product', $product->slug) : '#' }}">{{ $productName }}</a>
                                            </h3>
                                            @if($variantLabel)
                                                <div class="flex items-center gap-2 mt-1">
                                                    <i class="fas fa-palette text-[#9D8E1C] text-xs"></i>
                                                    <span class="text-xs text-gray-500">{{ $variantLabel }}</span>
                                                    @if($variant->color_hex)
                                                        <span class="w-3 h-3 rounded-full border border-gray-300" style="background-color: {{ $variant->color_hex }};"></span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-3 mt-2">
                                                <span class="text-sm font-bold text-[#9D8E1C]">{{ number_format($price, 0, ',', ' ') }} €</span>
                                            </div>
                                        </div>

                                        {{-- Prix total --}}
                                        <div class="text-right">
                                            <p class="text-sm text-gray-400">Total</p>
                                            <p class="text-lg font-bold text-[#9D8E1C]">{{ number_format($total, 0, ',', ' ') }} €</p>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex flex-wrap items-center justify-between gap-3 mt-4 pt-3 border-t border-gray-50">
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('shop.cart.update', $item->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ $item->quantity - 1 }}">
                                                <button type="submit" class="quantity-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus text-xs"></i>
                                                </button>
                                            </form>
                                            <span class="quantity-input text-sm font-medium">{{ $item->quantity }}</span>
                                            <form method="POST" action="{{ route('shop.cart.update', $item->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                <button type="submit" class="quantity-btn">
                                                    <i class="fas fa-plus text-xs"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <form method="POST" action="{{ route('shop.cart.remove', $item->id) }}" onsubmit="return confirm('Retirer cet article du panier ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm text-gray-400 hover:text-red-600 transition">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Résumé de la commande --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100 sticky top-24">
                        <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-receipt text-[#9D8E1C]"></i>
                            Résumé de la commande
                        </h3>

                        <div class="space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total</span>
                                <span class="font-medium">{{ number_format($cart->total(), 0, ',', ' ') }} €</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span><i class="fas fa-truck mr-1"></i>Livraison</span>
                                <span class="text-green-600">Gratuite</span>
                            </div>
                            <div class="border-t border-gray-100 my-2"></div>
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-[#9D8E1C]">{{ number_format($cart->total(), 0, ',', ' ') }} €</span>
                            </div>
                        </div>

                        @php
                            $shippingThreshold = 500;
                            $remaining = $shippingThreshold - $cart->total();
                        @endphp

                        @if($remaining > 0)
                            <div class="mt-3 p-3 bg-amber-50 rounded-xl text-sm">
                                <i class="fas fa-gift text-amber-600 mr-2"></i>
                                Plus que <strong class="text-amber-700">{{ number_format($remaining, 0, ',', ' ') }} €</strong> pour la livraison gratuite !
                            </div>
                        @endif

                        <a href="{{ route('shop.checkout') }}"
                           class="checkout-btn block w-full mt-6 py-3.5 rounded-xl text-center font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2"
                           style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                            <i class="fas fa-lock"></i>
                            Passer la commande
                        </a>

                        <p class="text-xs text-gray-400 text-center mt-4">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Paiement sécurisé
                        </p>
                    </div>
                </div>
            </div>

        @else
            {{-- Panier vide --}}
            <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium">Votre panier est vide</p>
                <p class="text-gray-400 text-sm mt-1">Découvrez nos collections et trouvez votre bonheur</p>
                <a href="{{ route('shop.catalogue') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 rounded-xl text-white transition-all duration-300" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                    <i class="fas fa-store"></i>
                    Découvrir les collections
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
