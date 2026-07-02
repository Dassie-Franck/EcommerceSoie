@props(['product'])

<div class="group card card-compact bg-base-100 shadow-sm hover:shadow-xl transition-all duration-300 border border-base-200">

    <figure class="relative aspect-[3/4] overflow-hidden">

        <a href="{{ route('shop.product', $product->slug) }}" class="block w-full h-full">

            <img
                src="{{ $product->primaryImage?->url ?? asset('images/placeholder.jpg') }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
            >

        </a>

        {{-- Bouton Favoris --}}
        <button
            class="absolute top-3 right-3 bg-white rounded-full p-2 shadow opacity-0 group-hover:opacity-100 transition duration-300">

            <svg xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke-width="2"
                 stroke="currentColor"
                 class="w-5 h-5">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.239-4.5-5-4.5-1.74 0-3.272.8-4.2 2.02C10.772 4.55 9.24 3.75 7.5 3.75c-2.761 0-5 2.015-5 4.5 0 7.22 9.3 12 9.3 12s9.2-4.78 9.2-12z"/>
            </svg>

        </button>

        {{-- Modal Tailles Fashion Nova --}}
        <div
            class="absolute bottom-0 left-0 right-0
                   translate-y-full
                   group-hover:translate-y-0
                   transition-all duration-300
                   bg-white/95 backdrop-blur-sm p-3">

            <p class="text-center text-xs font-semibold mb-2 uppercase">
                Choisir une taille
            </p>

            <div class="flex justify-center gap-2 flex-wrap">

                {{-- Exemple statique --}}
                <button class="btn btn-xs btn-outline">XS</button>
                <button class="btn btn-xs btn-outline">S</button>
                <button class="btn btn-xs btn-outline">M</button>
                <button class="btn btn-xs btn-outline">L</button>
                <button class="btn btn-xs btn-outline">XL</button>

                {{-- Plus tard tu pourras remplacer par :
                @foreach($product->sizes as $size)
                    <button class="btn btn-xs btn-outline">
                        {{ $size->name }}
                    </button>
                @endforeach
                --}}
            </div>

            <a href="{{ route('shop.product', $product->slug) }}"
               class="btn btn-primary btn-sm w-full mt-3">

                Ajouter au panier
            </a>

        </div>

    </figure>

    <div class="card-body">

        <h3 class="card-title text-sm font-medium line-clamp-2">
            {{ $product->name }}
        </h3>

        <p class="text-xs text-base-content/60">
            {{ $product->fabric_type }}
        </p>

        <div class="flex items-center justify-between mt-2">

            <span class="text-primary font-semibold">
                {{ number_format($product->base_price, 2) }} €
            </span>

            <a href="{{ route('shop.product', $product->slug) }}"
               class="btn btn-primary btn-xs">

                Voir
            </a>

        </div>

    </div>

</div>
