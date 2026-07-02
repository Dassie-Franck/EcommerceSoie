@extends('layouts.app')
@section('title', 'Finaliser la commande — AfriSoie')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .checkout-container {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .summary-card {
        background: linear-gradient(135deg, #fafaf7 0%, #ffffff 100%);
        border: 1px solid #e5e1d8;
    }

    .address-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .address-card:hover {
        border-color: #9D8E1C;
        background: #fefce8;
    }

    .address-card.selected {
        border-color: #9D8E1C;
        background: #fefce8;
        box-shadow: 0 0 0 2px rgba(157, 142, 28, 0.1);
    }

    .paypal-btn {
        transition: all 0.3s ease;
    }

    .paypal-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
    }

    .secure-badge {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-4xl checkout-container">

        {{-- En-tête --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-credit-card text-3xl text-[#9D8E1C]"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Finaliser la commande</h1>
                </div>
                <p class="text-gray-500 mt-1">
                    <i class="fas fa-lock mr-1 text-xs"></i>
                    Vos informations sont sécurisées
                </p>
            </div>
            <a href="{{ route('shop.cart') }}" class="text-sm text-gray-400 hover:text-[#9D8E1C] transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> Retour au panier
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('shop.checkout.process') }}" id="checkoutForm" class="space-y-6">
                    @csrf

                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-map-marker-alt text-[#9D8E1C]"></i>
                            <h2 class="font-semibold text-gray-800">Adresse de livraison</h2>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="space-y-3">
                                @foreach($addresses as $address)
                                    <label class="address-card flex items-start gap-3 p-3 rounded-xl border border-gray-200 cursor-pointer transition">
                                        <input type="radio"
                                               name="address_id"
                                               value="{{ $address->id }}"
                                               class="mt-1 w-4 h-4 accent-[#9D8E1C]"
                                               {{ $loop->first ? 'checked' : '' }}
                                               required>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-semibold text-gray-800">{{ $address->full_name }}</span>
                                                @if($address->is_default)
                                                    <span class="text-xs bg-[#9D8E1C]/10 text-[#9D8E1C] px-2 py-0.5 rounded-full">
                                                        <i class="fas fa-check-circle mr-1"></i>Par défaut
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">
                                                {{ $address->street }}, {{ $address->city }}
                                                @if($address->postal_code)
                                                    , {{ $address->postal_code }}
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <i class="fas fa-phone-alt mr-1 text-xs"></i>
                                                {{ $address->phone }}
                                            </p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-300 text-sm"></i>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-map-marker-alt text-gray-300 text-4xl mb-2"></i>
                                <p class="text-gray-500">Aucune adresse enregistrée</p>
                                <a href="{{ route('account.addresses') }}" class="inline-flex items-center gap-1 mt-2 text-sm text-[#9D8E1C] hover:underline">
                                    <i class="fas fa-plus-circle"></i> Ajouter une adresse
                                </a>
                            </div>
                        @endif

                        @error('address_id')
                            <p class="text-red-500 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-pen-alt text-[#9D8E1C]"></i>
                            <h2 class="font-semibold text-gray-800">Notes (optionnel)</h2>
                        </div>
                        <textarea name="notes"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#9D8E1C] focus:ring-2 focus:ring-[#9D8E1C]/20 outline-none transition resize-none"
                                  rows="3"
                                  placeholder="Instructions spéciales pour la livraison..."></textarea>
                    </div>

                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                        <div class="flex items-center gap-2 mb-4 pb-2 border-b border-gray-100">
                            <i class="fas fa-credit-card text-[#9D8E1C]"></i>
                            <h2 class="font-semibold text-gray-800">Mode de paiement</h2>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-200">
                            <div class="w-12 h-8 bg-[#003087] rounded flex items-center justify-center">
                                <i class="fab fa-paypal text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">PayPal</p>
                                <p class="text-xs text-gray-500">Paiement sécurisé par carte bancaire ou compte PayPal</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                            class="paypal-btn w-full py-4 rounded-xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-3 text-lg"
                            style="background: linear-gradient(135deg, #003087 0%, #001f5e 100%);">
                        <i class="fab fa-paypal text-xl"></i>
                        Payer avec PayPal
                    </button>

                    <div class="flex items-center justify-center gap-4 text-xs text-gray-400">
                        <span><i class="fas fa-lock mr-1"></i> Paiement sécurisé</span>
                        <span><i class="fas fa-undo-alt mr-1"></i> Retours gratuits</span>
                        <span><i class="fas fa-headset mr-1"></i> Support client</span>
                    </div>
                </form>
            </div>

            <div class="lg:col-span-1">
                <div class="summary-card bg-white rounded-2xl p-6 shadow-lg sticky top-24">
                    <h3 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-receipt text-[#9D8E1C]"></i>
                        Résumé
                    </h3>

                    @php
                        $cart = \App\Models\Cart::where('user_id', auth()->id())
                                    ->orWhere('session_id', session()->getId())
                                    ->with('items.productVariant.product')
                                    ->first();
                    @endphp

                    @if($cart && $cart->items->count() > 0)
                        <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                            @foreach($cart->items as $item)
                                @php
                                    $variant = $item->productVariant;
                                    $product = $variant ? $variant->product : null;
                                    $variantLabel = trim(($variant->size ?? '') . ' ' . ($variant->color ?? ''));

                                    // Calcul du prix avec sécurité
                                    $price = 0;
                                    if ($variant && $variant->finalPrice()) {
                                        $price = $variant->finalPrice();
                                    } elseif ($product && $product->price) {
                                        $price = $product->price;
                                    }
                                    $totalItemPrice = $item->quantity * $price;
                                @endphp
                                <div class="flex justify-between text-sm">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-800">{{ $item->quantity }}x</span>
                                        <span class="text-gray-600 ml-1">{{ $product ? $product->name : 'Produit indisponible' }}</span>
                                        @if($variantLabel)
                                            <span class="text-xs text-gray-400 block">({{ $variantLabel }})</span>
                                        @endif
                                    </div>
                                    <span class="font-semibold text-[#9D8E1C]">{{ number_format($totalItemPrice, 0, ',', ' ') }} €</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 my-4"></div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total</span>
                                <span>{{ number_format($cart->total(), 0, ',', ' ') }} €</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span><i class="fas fa-truck mr-1"></i>Livraison</span>
                                <span class="text-green-600">Gratuite</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span><i class="fas fa-percent mr-1"></i>Taxes (18%)</span>
                                <span>{{ number_format($cart->total() * 0.18, 0, ',', ' ') }} €</span>
                            </div>
                            <div class="border-t border-gray-100 my-2"></div>
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span class="text-[#9D8E1C]">{{ number_format($cart->total() * 1.18, 0, ',', ' ') }} €</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-shopping-cart text-gray-300 text-3xl mb-2"></i>
                            <p class="text-gray-500">Votre panier est vide</p>
                            <a href="{{ route('shop.catalogue') }}" class="inline-block mt-3 text-sm text-[#9D8E1C] hover:underline">
                                Continuer mes achats
                            </a>
                        </div>
                    @endif
                </div>

                <div class="secure-badge mt-4 p-3 rounded-xl flex items-center gap-3">
                    <i class="fas fa-shield-alt text-green-600 text-lg"></i>
                    <div class="text-xs text-green-700">
                        <p class="font-semibold">Paiement 100% sécurisé</p>
                        <p>Vos données bancaires sont cryptées</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gestion de la sélection d'adresse avec style visuel
    document.querySelectorAll('.address-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.address-card').forEach(c => {
                c.classList.remove('selected');
                c.style.borderColor = '#e5e7eb';
                c.style.background = 'white';
            });
            this.classList.add('selected');
            this.style.borderColor = '#9D8E1C';
            this.style.background = '#fefce8';

            // Cocher le radio bouton correspondant
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        });

        // Si déjà sélectionné, appliquer le style
        const radio = card.querySelector('input[type="radio"]');
        if (radio && radio.checked) {
            card.classList.add('selected');
            card.style.borderColor = '#9D8E1C';
            card.style.background = '#fefce8';
        }
    });
</script>
@endsection
