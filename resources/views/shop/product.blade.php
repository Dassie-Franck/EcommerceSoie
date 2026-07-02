@extends('layouts.app')
@section('title', $product->name . ' — AfriSoie')

@push('head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
    .product-gallery-thumb {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .product-gallery-thumb:hover {
        transform: scale(1.05);
        border-color: #9D8E1C !important;
    }
    .product-gallery-thumb.active {
        border-color: #9D8E1C !important;
        box-shadow: 0 0 0 2px rgba(157, 142, 28, 0.2);
    }
    .quantity-btn {
        transition: all 0.2s ease;
    }
    .quantity-btn:hover {
        background: #9D8E1C;
        color: white;
        border-color: #9D8E1C;
    }
    .wishlist-btn {
        transition: all 0.2s ease;
    }
    .wishlist-btn:hover {
        background: #9F1239;
        border-color: #9F1239;
        color: white;
    }
    .wishlist-btn.active {
        background: #9F1239;
        color: white;
    }
    .rating-star {
        cursor: pointer;
        transition: all 0.1s ease;
    }
    .rating-star:hover {
        transform: scale(1.1);
    }
    .review-card {
        transition: all 0.2s ease;
    }
    .review-card:hover {
        background: #f9fafb;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .product-details {
        animation: fadeIn 0.4s ease-out;
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('shop.home') }}" class="hover:text-[#9D8E1C] transition">
                <i class="fas fa-home mr-1"></i>Accueil
            </a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <a href="{{ route('shop.catalogue') }}" class="hover:text-[#9D8E1C] transition">
                <i class="fas fa-store mr-1"></i>Catalogue
            </a>
            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-gray-700 font-medium">{{ Str::limit($product->name, 40) }}</span>
        </nav>

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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 product-details">

            {{-- ── GALERIE IMAGES ───────────────────────────────────── --}}
            <div class="space-y-3">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-gray-100 shadow-lg relative group">
                    @if($product->images->first())
                        <img src="{{ Storage::url($product->images->first()->path) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-opacity duration-200"
                             id="main-image">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-6xl"></i>
                        </div>
                    @endif

                    @if($product->compare_price)
                        @php $discount = round((1 - $product->base_price / $product->compare_price) * 100); @endphp
                        <div class="absolute top-4 left-4">
                            <span class="bg-gradient-to-r from-[#9D8E1C] to-[#584F05] text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                <i class="fas fa-tag mr-1"></i>-{{ $discount }}%
                            </span>
                        </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $index => $image)
                            <button onclick="changeMainImage('{{ Storage::url($image->path) }}', this)"
                                    class="product-gallery-thumb flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 {{ $index === 0 ? 'border-[#9D8E1C]' : 'border-gray-200' }}">
                                <img src="{{ Storage::url($image->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── INFOS PRODUIT ────────────────────────────────────── --}}
            <div class="space-y-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-tag text-[#9D8E1C] text-sm"></i>
                        <p class="text-sm font-medium tracking-widest uppercase text-[#9D8E1C]">
                            {{ $product->category->name ?? 'Collection Premium' }}
                        </p>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mt-1">{{ $product->name }}</h1>
                    <div class="flex items-baseline gap-3 mt-4">
                        <span class="text-3xl font-bold text-[#9D8E1C]">{{ number_format($product->base_price, 0, ',', ' ') }} €</span>
                        @if($product->compare_price)
                            <span class="line-through text-gray-400 text-lg">{{ number_format($product->compare_price, 0, ',', ' ') }} €</span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 py-2">
                    @if($product->fabric_type)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center">
                                <i class="fas fa-tshirt text-[#9D8E1C]"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Tissu</p>
                                <p class="font-medium text-gray-700">{{ $product->fabric_type }}</p>
                            </div>
                        </div>
                    @endif
                    @if($product->origin)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-[#9D8E1C]"></i>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Origine</p>
                                <p class="font-medium text-gray-700">{{ $product->origin }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if($product->variants->count())
                    @php $firstVariant = $product->variants->where('is_active', true)->first(); @endphp
                    <form method="POST" action="{{ route('shop.cart.add') }}" class="space-y-5 border-t border-gray-100 pt-5" id="cartForm">
                        @csrf

                        @if($product->variants->count() > 1)
                            <div>
                                <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-palette text-[#9D8E1C]"></i> Variante
                                </label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="variants-container">
                                    @foreach($product->variants->where('is_active', true) as $variant)
                                        @php
                                            $isAvailable = $variant->stock_quantity > 0;
                                            $isLowStock = $variant->stock_quantity <= 5 && $variant->stock_quantity > 0;
                                        @endphp
                                        <label class="variant-option flex items-center gap-2 p-2 border rounded-xl cursor-pointer transition-all {{ $isAvailable ? 'hover:border-[#9D8E1C]' : 'opacity-50 cursor-not-allowed bg-gray-50' }}"
                                               data-variant-id="{{ $variant->id }}"
                                               data-price="{{ $variant->final_price }}"
                                               data-stock="{{ $variant->stock_quantity }}">
                                            <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                                   class="variant-radio w-4 h-4 accent-[#9D8E1C]"
                                                   {{ $isAvailable ? '' : 'disabled' }}
                                                   {{ $loop->first && $isAvailable ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-1">
                                                    @if($variant->color)
                                                        <span class="w-3 h-3 rounded-full border border-gray-300" style="background-color: {{ $variant->color_hex ?? '#9D8E1C' }};"></span>
                                                    @endif
                                                    <span class="text-sm font-medium">{{ $variant->size ?? '' }} {{ $variant->color ?? '' }}</span>
                                                </div>
                                                <p class="text-xs font-bold text-[#9D8E1C]">{{ number_format($product->base_price, 0, ',', ' ') }} €</p>
                                            </div>
                                            @if($isLowStock)
                                                <span class="text-xs text-orange-600"><i class="fas fa-exclamation-triangle"></i></span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php $selectedVariant = $product->variants->first(); @endphp
                            <input type="hidden" name="variant_id" value="{{ $selectedVariant->id }}">
                        @endif

                        <div>
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-cube text-[#9D8E1C]"></i> Quantité
                            </label>
                            <div class="flex items-center gap-3">
                                <button type="button" onclick="const q=document.getElementById('qty'); if(q.value>1) q.value--;" class="quantity-btn w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $firstVariant->stock_quantity ?? 10 }}" class="border-gray-300 rounded-xl w-20 text-center font-semibold">
                                <button type="button" onclick="const q=document.getElementById('qty'); const max={{ $firstVariant->stock_quantity ?? 10 }}; if(q.value<max) q.value++;" class="quantity-btn w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Boutons action --}}
                        <div class="flex gap-3 pt-2">
                            <button type="submit" id="add-to-cart-btn" class="flex-1 py-3 rounded-xl font-semibold text-white transition-all duration-300 flex items-center justify-center gap-2" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                                <i class="fas fa-shopping-cart"></i> Ajouter au panier
                            </button>

                            @auth
                                @php
                                    $isInWishlist = auth()->user()->wishlist?->hasProductVariant($firstVariant?->id ?? 0);
                                @endphp
                                <button type="button"
                                        id="wishlist-btn"
                                        data-variant-id="{{ $firstVariant?->id }}"
                                        onclick="toggleWishlist(parseInt(this.dataset.variantId), this)"
                                        class="wishlist-btn w-12 h-12 rounded-xl border border-gray-300 flex items-center justify-center transition-all"
                                        title="{{ $isInWishlist ? 'Retirer des favoris' : 'Ajouter aux favoris' }}">
                                    <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
                                </button>
                                @if($isInWishlist)
                                    <style>
                                        #wishlist-btn { background: #9F1239; color: white; border-color: #9F1239; }
                                    </style>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-12 h-12 rounded-xl border border-gray-300 flex items-center justify-center hover:bg-gray-100">
                                    <i class="far fa-heart text-gray-500"></i>
                                </a>
                            @endauth
                        </div>
                    </form>
                @else
                    <div class="p-4 rounded-lg bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700">
                        <i class="fas fa-exclamation-triangle"></i> Ce produit n'a pas encore de variantes disponibles.
                    </div>
                @endif

                @if($product->care_instructions)
                    <div class="border-t border-gray-100 pt-4">
                        <details class="group">
                            <summary class="flex items-center gap-2 text-sm font-semibold text-gray-700 cursor-pointer list-none">
                                <i class="fas fa-leaf text-[#9D8E1C]"></i> Instructions d'entretien
                                <i class="fas fa-chevron-down ml-auto group-open:rotate-180 transition"></i>
                            </summary>
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-600">
                                <i class="fas fa-tint mr-2 text-[#9D8E1C]"></i> {{ $product->care_instructions }}
                            </div>
                        </details>
                    </div>
                @endif

                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <i class="fas fa-truck text-[#9D8E1C]"></i> Livraison sous 2-5 jours ouvrés
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-500 mt-2">
                        <i class="fas fa-undo-alt text-[#9D8E1C]"></i> Retours gratuits sous 14 jours
                    </div>
                </div>
            </div>
        </div>

        {{-- ── AVIS CLIENTS ─────────────────────────────────────────── --}}
        <div class="mt-16 border-t border-gray-100 pt-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-star text-[#9D8E1C]"></i> Avis clients
                <span class="text-base font-normal text-gray-400 ml-2">({{ $product->reviews->where('is_approved', true)->count() }} avis)</span>
            </h2>

            @auth
                <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-8 shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-pen-alt text-[#9D8E1C]"></i> Laisser un avis
                    </h3>
                    <form method="POST" action="{{ route('shop.review.store', $product) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Votre note</label>
                            <div class="flex gap-1 rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="far fa-star text-2xl text-gray-300 rating-star cursor-pointer" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', 5) }}" required>
                        </div>
                        <div>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Titre de votre avis (optionnel)" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:border-[#9D8E1C] focus:ring-2 focus:ring-[#9D8E1C]/20 outline-none">
                        </div>
                        <div>
                            <textarea name="comment" rows="3" placeholder="Partagez votre expérience..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:border-[#9D8E1C] focus:ring-2 focus:ring-[#9D8E1C]/20 outline-none resize-none" required>{{ old('comment') }}</textarea>
                        </div>
                        <button type="submit" class="px-6 py-2.5 rounded-xl font-semibold text-white transition-all duration-300 flex items-center gap-2" style="background: linear-gradient(135deg, #9D8E1C 0%, #584F05 100%);">
                            <i class="fas fa-paper-plane"></i> Publier mon avis
                        </button>
                    </form>
                </div>
            @else
                <div class="mb-6 p-4 rounded-lg bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                    <a href="{{ route('login') }}" class="font-semibold underline">Connectez-vous</a> pour laisser un avis.
                </div>
            @endauth

            @php $approvedReviews = $product->reviews->where('is_approved', true); @endphp
            @if($approvedReviews->count())
                <div class="space-y-4">
                    @foreach($approvedReviews as $review)
                        <div class="review-card bg-white rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition">
                            <div class="flex flex-wrap justify-between items-start gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-[#9D8E1C] to-[#584F05] flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $review->user->name }}</p>
                                        <div class="flex gap-0.5 mt-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                            @if($review->title)<p class="font-medium text-gray-800 mt-3">{{ $review->title }}</p>@endif
                            <p class="text-gray-600 text-sm mt-2">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <i class="fas fa-comment-dots text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">Aucun avis pour le moment.</p>
                    <p class="text-gray-400 text-sm">Soyez le premier à donner votre avis !</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// ── Changement image galerie ─────────────────────────────────
function changeMainImage(src, element) {
    const mainImg = document.getElementById('main-image');
    mainImg.style.opacity = '0';
    setTimeout(() => { mainImg.src = src; mainImg.style.opacity = '1'; }, 200);
    document.querySelectorAll('.product-gallery-thumb').forEach(t => t.style.borderColor = '#E5E7EB');
    element.style.borderColor = '#9D8E1C';
}

// ── Étoiles notation ─────────────────────────────────────────
function initStars(defaultVal) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, i) => {
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, j) => s.className = `${j <= i ? 'fas' : 'far'} fa-star text-2xl rating-star cursor-pointer ${j <= i ? 'text-yellow-400' : 'text-gray-300'}`);
        });
        star.addEventListener('click', () => {
            const val = parseInt(star.dataset.value);
            document.getElementById('rating-value').value = val;
            stars.forEach((s, j) => s.className = `${j < val ? 'fas' : 'far'} fa-star text-2xl rating-star cursor-pointer ${j < val ? 'text-yellow-400' : 'text-gray-300'}`);
        });
    });
    document.querySelector('.rating-stars')?.addEventListener('mouseleave', () => {
        const val = parseInt(document.getElementById('rating-value')?.value || defaultVal);
        stars.forEach((s, j) => s.className = `${j < val ? 'fas' : 'far'} fa-star text-2xl rating-star cursor-pointer ${j < val ? 'text-yellow-400' : 'text-gray-300'}`);
    });
    stars.forEach((s, j) => s.className = `${j < defaultVal ? 'fas' : 'far'} fa-star text-2xl rating-star cursor-pointer ${j < defaultVal ? 'text-yellow-400' : 'text-gray-300'}`);
}
initStars({{ old('rating', 5) }});

// ── Sélection variante : mise à jour wishlist btn + qty max ──
document.querySelectorAll('.variant-radio').forEach(radio => {
    radio.addEventListener('change', function () {
        const wrap = this.closest('.variant-option');
        const varId = parseInt(wrap.dataset.variantId);
        const stock = parseInt(wrap.dataset.stock) || 10;
        const wBtn = document.getElementById('wishlist-btn');
        if (wBtn) wBtn.dataset.variantId = varId;
        const qty = document.getElementById('qty');
        if (qty) { qty.max = stock; if (parseInt(qty.value) > stock) qty.value = stock; }
        const cartBtn = document.getElementById('add-to-cart-btn');
        if (cartBtn) cartBtn.disabled = stock === 0;
    });
});

// ── Toggle wishlist (AJAX) ───────────────────────────────────
function toggleWishlist(variantId, btn) {
    if (!variantId) return;
    btn.style.transform = 'scale(.88)';
    setTimeout(() => btn.style.transform = '', 150);
    fetch('{{ route("shop.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_variant_id: variantId })
    })
    .then(r => { if (r.status === 401) { window.location.href = '{{ route("login") }}'; return null; } return r.json(); })
    .then(data => {
        if (!data) return;
        if (data.success) {
            const icon = btn.querySelector('i');
            if (data.added) {
                icon.className = 'fas fa-heart';
                btn.style.background = '#9F1239'; btn.style.color = '#fff'; btn.style.borderColor = '#9F1239';
                btn.title = 'Retirer des favoris';
            } else {
                icon.className = 'far fa-heart';
                btn.style.background = ''; btn.style.color = ''; btn.style.borderColor = '#D1D5DB';
                btn.title = 'Ajouter aux favoris';
            }
            const badge = document.getElementById('wishlist-count');
            if (badge) { badge.textContent = data.count; badge.classList.toggle('hidden', data.count === 0); }
            showToast(data.message, data.added ? 'success' : 'info');
        } else showToast(data.message || 'Erreur', 'error');
    })
    .catch(() => showToast('Erreur réseau.', 'error'));
}

// ── Toast notification ───────────────────────────────────────
function showToast(message, type = 'success') {
    document.querySelectorAll('.es-toast').forEach(t => t.remove());
    const colors = { success: { bg: '#166534', icon: 'fa-check-circle' }, error: { bg: '#9F1239', icon: 'fa-exclamation-circle' }, info: { bg: '#1E40AF', icon: 'fa-info-circle' } };
    const c = colors[type] || colors.info;
    const t = document.createElement('div');
    t.className = 'es-toast';
    t.style.cssText = `position:fixed; bottom:1.5rem; right:1.5rem; z-index:9999; display:flex; align-items:center; gap:.6rem; background:${c.bg}; color:#fff; padding:.75rem 1.25rem; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.15); font-size:.875rem; transform:translateY(10px); opacity:0; transition:transform .25s ease, opacity .25s ease;`;
    t.innerHTML = `<i class="fas ${c.icon}"></i><span>${message}</span>`;
    document.body.appendChild(t);
    requestAnimationFrame(() => { t.style.transform = 'translateY(0)'; t.style.opacity = '1'; });
    setTimeout(() => { t.style.transform = 'translateY(10px)'; t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3200);
}
</script>
@endsection
