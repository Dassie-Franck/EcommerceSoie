@extends('layouts.app')

@section('title', 'Accueil')

@section('content')

<section
    class="relative h-[90vh] overflow-hidden">

    <img
        src="{{ asset('images/baniere.jpeg') }}"
        alt="Collection Soie"
        class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-black/25"></div>

    <div class="relative z-10 flex items-center justify-center h-full">

        <div class="text-center text-white">



            {{-- <div class="mt-8 flex justify-center gap-4">

                <a href="#"
                   class="btn btn-primary btn-lg" style="background: linear-gradient(90deg, #9D8E1C, #584F05, #978607);">
                    Acheter
                </a>

                <a href="#"
                   class="btn btn-outline btn-lg text-white border-white" style="background: linear-gradient(#584F05);">
                    Explorer
                </a>

            </div> --}}

        </div>

    </div>

</section>


{{-- ============================================================
     SECTION 3 : GOLDEN HOURS - STYLES MADE FOR SUMMER SUNSETS
     ============================================================ --}}
<section class="relative py-20 overflow-hidden bg-gradient-to-br from-stone-100 via-amber-100 to-yellow-100">

    {{-- Contexte décoratif --}}
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 left-0 w-72 h-72 bg-yellow-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-600 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-yellow-700 rounded-full mix-blend-multiply filter blur-3xl animate-pulse delay-2000"></div>
    </div>

    {{-- Rayons de soleil animés (arrière-plan) --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-1/2 -left-1/2 w-full h-full">
            <div class="absolute top-1/4 left-1/4 w-1 h-32 bg-gradient-to-b from-yellow-600/0 via-yellow-600/60 to-yellow-600/0 transform rotate-45 animate-sunbeam"></div>
            <div class="absolute top-1/3 right-1/3 w-1 h-40 bg-gradient-to-b from-amber-600/0 via-amber-600/50 to-amber-600/0 transform -rotate-12 animate-sunbeam-delay"></div>
            <div class="absolute bottom-1/4 left-1/2 w-1 h-28 bg-gradient-to-b from-yellow-700/0 via-yellow-700/40 to-yellow-700/0 transform rotate-90 animate-sunbeam-delay-2"></div>
        </div>
    </div>

    <div class="container mx-auto px-4 relative z-10">

        {{-- Grille principale : texte à gauche, visuel à droite --}}
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

            {{-- Partie texte --}}
            <div class="lg:w-1/2 text-center lg:text-left space-y-6 animate-fadeInUp">

                {{-- Petite étiquette --}}
                <div class="inline-block px-4 py-1.5 bg-white/60 backdrop-blur-sm rounded-full text-xs font-semibold tracking-wider text-yellow-800 border border-yellow-500/50 shadow-sm">
                     LIMITED COLLECTION
                </div>

                {{-- Titre principal avec effet dégradé --}}
                <div class="space-y-2">
                    <p class="text-sm md:text-base tracking-[0.3em] text-yellow-700 uppercase font-light">
                       Eclat Soie
                    </p>

                    <h2 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-[1.1] tracking-tight">
                        <span class="bg-gradient-to-r from-yellow-700 via-amber-600 to-yellow-800 bg-clip-text text-transparent">
                            Off
                        </span>
                        <br>
                        <span class="bg-gradient-to-r from-amber-600 via-yellow-700 to-yellow-900 bg-clip-text text-transparent">
                            Sales
                        </span>
                    </h2>

                    <div class="flex items-center justify-center lg:justify-start gap-3 my-4">
                        <div class="w-12 h-px bg-gradient-to-r from-amber-600 to-transparent"></div>
                        <span class="text-2xl md:text-3xl font-light text-yellow-700">&</span>
                        <div class="w-12 h-px bg-gradient-to-l from-amber-600 to-transparent"></div>
                    </div>

                    <h3 class="text-4xl md:text-6xl lg:text-7xl font-bold tracking-wide">
                        <span class="bg-gradient-to-r from-yellow-600 via-amber-600 to-yellow-700 bg-clip-text text-transparent">
                            20-50%
                        </span>
                    </h3>

                    <div class="text-3xl md:text-5xl lg:text-6xl font-light text-yellow-700 tracking-wider mt-2">
                        Golden Hours
                    </div>
                </div>

                {{-- Description --}}
                <p class="text-stone-700 max-w-md mx-auto lg:mx-0 text-base md:text-lg leading-relaxed">
                    Découvrez notre collection exclusive de vêtements en soie, conçue pour allier raffinement, confort et élégance intemporelle. Chaque pièce se distingue par sa texture douce et luxueuse, ainsi que par une qualité de confection exceptionnelle qui sublime votre style au quotidien.
                </p>

                {{-- Boutons CTA --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="#"
                       class="group relative inline-flex items-center justify-center px-8 py-3.5 overflow-hidden rounded-full bg-gradient-to-r from-yellow-700 via-amber-600 to-yellow-800 text-white font-semibold shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                        <span class="relative z-10 flex items-center gap-2">
                            SHOP NOW
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-yellow-800 via-amber-700 to-yellow-900 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </a>

                    <a href="#"
                       class="inline-flex items-center justify-center px-8 py-3.5 rounded-full border-2 border-amber-600 text-yellow-700 font-semibold hover:bg-amber-600 hover:text-white transition-all duration-300">
                        Explorer la collection
                    </a>
                </div>

                {{-- Indicateurs --}}
                <div class="flex flex-wrap gap-6 justify-center lg:justify-start pt-6">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        <span class="text-sm text-stone-600">Édition Limitée</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-stone-600">Livraison Offerte</span>
                    </div>
                </div>
            </div>

            {{-- Partie visuelle (collage d'images) --}}
            <div class="lg:w-1/2">
                <div class="relative grid grid-cols-2 gap-3 auto-rows-min">

                    {{-- Image principale (grande) --}}
                    <div class="col-span-2 row-span-2 relative rounded-2xl overflow-hidden shadow-2xl transform hover:scale-[1.02] transition-transform duration-500">
                        <img
                            src="{{ asset('images/golden-hours-main.jpg') }}"
                            alt="Summer Sunset Collection"
                            class="w-full h-full object-cover aspect-[4/3]"
                            loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                    </div>

                    {{-- Image 2 (petite) --}}
                    <div class="relative rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-500">
                        <img
                            src="{{ asset('images/golden-hours-2.jpg') }}"
                            alt="Golden Hour Style"
                            class="w-full aspect-square object-cover"
                            loading="lazy">
                    </div>

                    {{-- Image 3 (petite avec badge) --}}
                    <div class="relative rounded-xl overflow-hidden shadow-lg transform hover:scale-105 transition-transform duration-500">
                        <img
                            src="{{ asset('images/golden-hours-3.jpg') }}"
                            alt="Beyond Golden Hours"
                            class="w-full aspect-square object-cover"
                            loading="lazy">
                        <div class="absolute bottom-2 right-2 bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-bold text-yellow-700">
                            New ✦
                        </div>
                    </div>

                    {{-- Élément décoratif flottant --}}
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-gradient-to-br from-yellow-600 to-amber-600 rounded-full opacity-70 blur-2xl animate-pulse"></div>
                    <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-gradient-to-tr from-amber-600 to-yellow-700 rounded-full opacity-60 blur-2xl animate-pulse delay-700"></div>
                </div>
            </div>
        </div>
    </div>
</section>
{{-- ============================================================
     SECTION 2 : THE TREND REPORT
     ============================================================ --}}
<section class="container mx-auto px-4 py-12">

    <h2 class="text-2xl font-black uppercase tracking-tight mb-6">The Trend Report</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        {{-- Carte 1 --}}
        <a href="#" class="group block">
            <div class="relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/robe-rose-01.jpg') }}
                    alt="BodyCTRL: Second Skin"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
            </div>
            <p class="mt-2 text-sm font-semibold text-center">
                BodyCTRL: Dresses
                <span class="ml-1">›</span>
            </p>
        </a>

        {{-- Carte 2 --}}
        <a href="#" class="group block">
            <div class="relative overflow-hidden">
                <img
                     src={{ asset('storage/categories/short-bleu-rouge-02.jpg') }}
                    alt="Vacation Strolls"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
            </div>
            <p class="mt-2 text-sm font-semibold text-center">
                Sets Short
                <span class="ml-1">›</span>
            </p>
        </a>

        {{-- Carte 3 --}}
        <a href="#" class="group block">
            <div class="relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/pantalonHomme-blanc-categories-02.jpeg') }}
                    alt="Hotter on Vacation"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
            </div>
            <p class="mt-2 text-sm font-semibold text-center">
                Pants
                <span class="ml-1">›</span>
            </p>
        </a>

        {{-- Carte 4 --}}
        <a href="#" class="group block">
            <div class="relative overflow-hidden">
                <img
                     src={{ asset('storage/categories/set-noir-blanc-femme-categories-03.jpg') }}
                    alt="Swim Escape"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
            </div>
            <p class="mt-2 text-sm font-semibold text-center">
                Sets Long
                <span class="ml-1">›</span>
            </p>
        </a>

    </div>
</section>


{{-- ============================================================
     SECTION 3 : SHOP BY CATEGORY
     ============================================================ --}}
<section class="container mx-auto px-4 py-12">

    <h2 class="text-2xl font-black uppercase tracking-tight mb-6">Shop By Category</h2>

    {{-- Grille principale : image héro à gauche + 2 colonnes de catégories à droite --}}
    <div class="flex flex-col lg:flex-row gap-3">

        {{-- Image héro (grande, colonne gauche) --}}
        <a href="#" class="group block lg:w-1/3 flex-shrink-0">
            <div class="relative overflow-hidden h-full">
                <img
                    src={{ asset('storage/categories/kimonos-noir-02.jpg') }}
                    alt="Nouvelle collection"
                    class="w-full h-full object-cover min-h-[480px] transition-transform duration-500 group-hover:scale-105">
            </div>
        </a>

        {{-- Grille 2×2 de catégories à droite --}}
        <div class="flex-1 grid grid-cols-2 gap-3">

            {{-- Rangée 1 --}}
            <a href="#" class="group relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/junmpsuite-categories-01.jpg') }}
                    alt="Matching Sets"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-3 left-3 text-white font-bold text-sm uppercase tracking-wide drop-shadow">
                    Matching Sets
                </span>
            </a>

            <a href="#" class="group relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/dresses-blanc-categories-01.jpg') }}
                    alt="Shorts"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-3 left-3 text-white font-bold text-sm uppercase tracking-wide drop-shadow">
                    Dresses
                </span>
            </a>

            {{-- Rangée 2 --}}
            <a href="#" class="group relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/dresses-noir-blanc-categories-04.jpg') }}
                    alt="Swim"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-3 left-3 text-white font-bold text-sm uppercase tracking-wide drop-shadow">
                    Dresses Short
                </span>
            </a>

            {{-- Sous-grille accessoires (earrings + heels côte à côte) --}}
            <div class="grid grid-cols-2 gap-3">
                <a href="#" class="group relative overflow-hidden ">
                    <img
                        src={{ asset('storage/categories/dresses-vert-categories-03.jpg') }}
                        alt="Bijoux"
                        class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
                <a href="#" class="group relative overflow-hidden bg-gray-50">
                    <img
                        src={{ asset('storage/categories/kimonos-noir-01.jpg') }}
                        alt="Chaussures"
                        class="w-full aspect-square object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
                <a href="#" class="group relative overflow-hidden">
                <img
                    src={{ asset('storage/categories/kimonos-noir-01.jpg') }}
                    alt="Shorts"
                    class="w-full aspect-[3/4] object-cover transition-transform duration-500 group-hover:scale-105">
                <span class="absolute bottom-3 left-3 text-white font-bold text-sm uppercase tracking-wide drop-shadow">
                    Shorts
                </span>
            </a>
            </div>

        </div>
    </div>
</section>
{{-- ============================================================
     SECTION 4 : SHOP THE LATEST
     ============================================================ --}}
<section class="container mx-auto px-4 py-12" id="shop-latest">

    <h2 class="text-2xl font-black uppercase tracking-tight mb-4">Shop The Latest</h2>

    {{-- Onglets --}}
    @php
        $tabs = [
            'for-you'    => '✦ For You',
            'New In'   => 'New In',
            'eclatdeals' => 'Eclat Deals',
            'dresses'    => 'Dresses',
            'pants'      => 'Pants',
            'kimonos'    => 'Kimonos',
            'Matching Sets'       => 'Matching Sets',
            'shorts'     => 'Shorts',
        ];
    @endphp

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach($tabs as $key => $label)
            <button
                data-tab="{{ $key }}"
                onclick="loadProducts('{{ $key }}')"
                class="tab-btn px-4 py-2 rounded-full text-sm font-semibold transition-colors border
                       {{ $key === 'for-you' ? 'bg-black text-white border-black' : 'bg-white text-black border-gray-300 hover:border-black' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Zone produits --}}
    <div id="products-grid" class="relative min-h-[400px]">

        {{-- Spinner --}}
        <div id="products-loader" class="hidden absolute inset-0 flex items-center justify-center bg-white/70 z-10">
            <svg class="animate-spin w-8 h-8 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
        </div>

        {{-- Grille injectée par JS --}}
        <div id="products-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 transition-opacity duration-200">
            {{-- Rempli au DOMContentLoaded --}}
        </div>

    </div>

</section>

<script>
// ── Template carte produit (miroir exact de ton Blade) ───────
function productCard(p) {

    // Badge
    let badge = '';
    if (p.discount) {
        badge = `<span class="absolute top-2 left-2 z-10 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 uppercase tracking-wide rounded-sm">
                    -${Math.round(p.discount)}%
                 </span>`;
    } else if (p.is_featured) {
        badge = `<span class="absolute top-2 left-2 z-10 bg-black text-white text-[10px] font-bold px-2 py-0.5 uppercase tracking-wide rounded-sm">
                    New
                 </span>`;
    }

    // Image
    const image = p.image_url
        ? `<img src="${p.image_url}"
                alt="${p.image_alt}"
                class="w-full aspect-[2/3] object-cover transition-transform duration-500 group-hover:scale-105"
                loading="lazy">`
        : `<div class="w-full aspect-[2/3] flex items-center justify-center bg-gray-200 text-gray-400 text-xs">
               Pas d'image
           </div>`;

    // Prix barré
    const oldPrice = p.compare_price
        ? `<span class="text-xs text-gray-400 line-through">${p.compare_price} €</span>`
        : '';

    // Swatches
    const swatches = p.colors.length
        ? `<div class="flex gap-1 mt-1.5 flex-wrap">
               ${p.colors.map(c =>
                   `<span title="${c.name}"
                          class="w-4 h-4 rounded-full border border-gray-300 cursor-pointer hover:scale-110 transition-transform"
                          style="background-color:${c.hex}">
                    </span>`
               ).join('')}
           </div>`
        : '';

    return `
        <a href="${p.url}" class="group relative block">

            ${badge}

            <button onclick="event.preventDefault()"
                    class="absolute top-2 right-2 z-10 p-1 rounded-full bg-white/70 hover:bg-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                </svg>
            </button>

            <div class="overflow-hidden bg-gray-100">
                ${image}
            </div>

            <div class="mt-2">
                <p class="text-xs font-medium leading-tight line-clamp-2">${p.name}</p>

                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-sm font-bold">${p.base_price} €</span>
                    ${oldPrice}
                </div>

                ${swatches}
            </div>

        </a>`;
}

// ── Chargement AJAX ─────────────────────────────────────────
async function loadProducts(tab) {

    // Mettre à jour les onglets
    document.querySelectorAll('.tab-btn').forEach(btn => {
        const isActive = btn.dataset.tab === tab;
        btn.classList.toggle('bg-black',     isActive);
        btn.classList.toggle('text-white',   isActive);
        btn.classList.toggle('border-black', isActive);
        btn.classList.toggle('bg-white',    !isActive);
        btn.classList.toggle('text-black',  !isActive);
        btn.classList.toggle('border-gray-300', !isActive);
    });

    // Afficher loader + opacité réduite
    const list   = document.getElementById('products-list');
    const loader = document.getElementById('products-loader');
    loader.classList.remove('hidden');
    list.style.opacity = '0.3';

    try {
        const res = await fetch(`/api/products/latest?tab=${tab}`);
        const data = await res.json();

        list.innerHTML = data.length
            ? data.map(productCard).join('')
            : `<p class="col-span-4 text-center text-gray-400 py-16">Aucun produit disponible.</p>`;

    } catch (e) {
        console.error('Erreur chargement produits :', e);
        list.innerHTML = `<p class="col-span-4 text-center text-red-400 py-16">Une erreur est survenue.</p>`;
    } finally {
        loader.classList.add('hidden');
        list.style.opacity = '1';
    }
}

// ── Chargement initial ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => loadProducts('for-you'));
</script>
@endsection
