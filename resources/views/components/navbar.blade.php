<header class="w-full">

    {{-- ══════════════════════════════════════════
         🔝 TOP BAR — Promo + Countdown
    ══════════════════════════════════════════ --}}
    <div class="w-full text-white text-xs py-2 flex justify-center items-center gap-6 text-center"
         style="background: linear-gradient(90deg, #7c7011, #ad9e27, #695d02);">

        <span class="font-semibold tracking-wide uppercase">
            LAST DAY! BUY ONE, GET ONE FREE — ROBES, ENSEMBLES & COMBINAISONS
            <span class="bg-white text-black px-2 py-0.5 rounded font-bold ml-1">FREE</span>
        </span>

        <span class="font-bold tracking-widest" id="navbar-countdown">13:17:31</span>

        <a href="{{ route('shop.catalogue') }}"
           class="underline font-semibold tracking-wide nav-link-hover">
            Shop Now
        </a>
    </div>

    {{-- ══════════════════════════════════════════
          MAIN HEADER
    ══════════════════════════════════════════ --}}
    <div class="bg-white w-full border-b border-gray-100">
        <div class="w-full px-10 py-4 flex items-center justify-between">

            {{-- ── GAUCHE : Logo + Menu principal ── --}}
            <div class="flex items-center gap-10">

                {{-- LOGO --}}
                <a href="{{ route('shop.home') }}">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="AfriSoie"
                         class="h-20 w-auto object-contain">
                </a>

                {{-- MENU PRINCIPAL --}}
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium tracking-wide">
                    <a href="{{ route('shop.catalogue') }}"
                       class="nav-link border-b-2 border-black pb-0.5">WOMEN</a>
                    <a href="{{ route('shop.catalogue') }}" class="nav-link">PLUS+CURVE</a>
                    <a href="{{ route('shop.catalogue') }}" class="nav-link">MEN</a>
                    <a href="{{ route('shop.catalogue') }}" class="nav-link relative">
                        SPORT
                        <span class="absolute -top-2.5 -right-5 text-[9px] bg-red-500 text-white px-1 py-0.5 rounded font-bold">
                            NEW
                        </span>
                    </a>
                    <a href="{{ route('shop.catalogue') }}" class="nav-link">KIDS</a>
                    <a href="{{ route('shop.catalogue') }}" class="nav-link">BEAUTY</a>
                </nav>
            </div>

            {{-- ── DROITE : Recherche + Icônes ── --}}
            <div class="hidden md:flex items-center gap-5">

                {{-- BARRE DE RECHERCHE animée --}}
                <div class="search-wrapper" id="search-wrapper">
                    <div class="search-ring" aria-hidden="true"></div>
                    <div class="search-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0"
                             style="color:#9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="main-search"
                               placeholder="Rechercher des vêtements...">
                    </div>
                </div>

                {{-- ── ICÔNES ── --}}
                <div class="flex items-center gap-1">

                    {{-- LANGUE --}}
                    <div class="dropdown dropdown-end">
                        <label tabindex="0" class="icon-btn" aria-label="Langue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 21a9 9 0 100-18 9 9 0 000 18zm0 0c-2.5 0-4.5-4-4.5-9s2-9 4.5-9m0 18c2.5 0 4.5-4 4.5-9s-2-9-4.5-9M3.5 9h17M3.5 15h17"/>
                            </svg>
                        </label>
                        <ul tabindex="0"
                            class="dropdown-content menu shadow-lg bg-white border border-gray-100 rounded-lg w-36 text-sm mt-2 p-1">
                            <li><a class="nav-link text-xs font-medium tracking-wide py-2">🇫🇷 Français</a></li>
                            <li><a class="nav-link text-xs font-medium tracking-wide py-2">🇺🇸 English</a></li>
                        </ul>
                    </div>

                    {{-- FAVORIS --}}
                    @auth
                        <a href="{{ route('account.wishlist') }}" class="icon-btn" aria-label="Favoris">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.682l1.318-1.364a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="icon-btn" aria-label="Favoris">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.682l1.318-1.364a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                            </svg>
                        </a>
                    @endauth

                    {{-- COMPTE --}}
                    @auth
                        <div class="dropdown dropdown-end">
                            <label tabindex="0" class="icon-btn" aria-label="Mon compte">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                         alt="{{ auth()->user()->name }}"
                                         class="w-5 h-5 rounded-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                @endif
                            </label>
                            <ul tabindex="0"
                                class="dropdown-content menu shadow-lg bg-white border border-gray-100 rounded-lg w-52 mt-2 p-1">
                                <li class="px-3 py-2 border-b border-gray-100 mb-1">
                                    <p class="text-xs font-semibold tracking-wide text-gray-800">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                                </li>
                                <li><a href="{{ route('account.profile') }}"
                                       class="nav-link text-xs font-medium tracking-wide py-2">Mon profil</a></li>
                                <li><a href="{{ route('account.orders') }}"
                                       class="nav-link text-xs font-medium tracking-wide py-2">Mes commandes</a></li>
                                <li><a href="{{ route('account.wishlist') }}"
                                       class="nav-link text-xs font-medium tracking-wide py-2">Mes favoris</a></li>
                                <li><a href="{{ route('account.addresses') }}"
                                       class="nav-link text-xs font-medium tracking-wide py-2">Mes adresses</a></li>
                                @if(auth()->user()->role === 'admin')
                                    <li class="border-t border-gray-100 mt-1">
                                        <a href="{{ route('admin.dashboard') }}"
                                           class="text-xs font-bold tracking-wide py-2 px-3"
                                           style="color:#9D8E1C;">
                                            Administration
                                        </a>
                                    </li>
                                @endif
                                <li class="border-t border-gray-100 mt-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="w-full text-left text-xs font-medium tracking-wide text-red-500 px-3 py-2 hover:bg-red-50 rounded-lg transition-colors">
                                            Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="icon-btn" aria-label="Connexion">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                    @endauth

                    {{-- PANIER --}}
                    @php
                        use App\Models\Cart;
                        $cartCount = \App\Models\Cart::getCartCount();
                    @endphp
                    <a href="{{ route('shop.cart') }}" class="icon-btn relative" aria-label="Panier">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M7 13L5.4 5M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="cart-badge">
                                {{ $cartCount > 99 ? '99+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                </div>
            </div>

            {{-- ── MOBILE : Hamburger + Panier ── --}}
            <div class="flex md:hidden items-center gap-3">
                <a href="{{ route('shop.cart') }}" class="icon-btn relative">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 6h13M7 13L5.4 5M10 21a1 1 0 100-2 1 1 0 000 2zm7 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <button class="icon-btn" onclick="toggleMobileMenu()" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>

        {{-- ── SECOND MENU — centré, même style que le menu principal ── --}}
        <div class="w-full flex justify-center py-2.5">
            <nav class="flex items-center gap-6 text-xs font-medium tracking-wide overflow-x-auto">
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">NEW IN</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">CLOTHING</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">Eclatdeals</a>
                <a href="{{ route('shop.catalogue') }}"
                   class="whitespace-nowrap font-bold nav-link"
                   style="color:#9D8E1C;">DRESSES</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">PANTS</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">MATCHING SETS</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">SHORT</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">KIMONOS</a>
                {{-- <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">SHOES</a>
                <a href="{{ route('shop.catalogue') }}" class="nav-link whitespace-nowrap">ACCESSORIES</a> --}}
                <a href="{{ route('shop.catalogue') }}"
                   class="whitespace-nowrap font-bold"
                   style="color:#dc2626;">SALE</a>
            </nav>
        </div>

    </div>

    {{-- ── MENU MOBILE (caché par défaut) ── --}}
    <div id="mobile-menu"
         class="hidden md:hidden bg-white border-t border-gray-100 px-6 py-4 space-y-3">
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">WOMEN</a>
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">PLUS+CURVE</a>
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">MEN</a>
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">SPORT</a>
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">KIDS</a>
        <a href="{{ route('shop.catalogue') }}" class="block text-sm font-medium tracking-wide nav-link py-1">BEAUTY</a>
        <div class="border-t border-gray-100 pt-3 space-y-2">
            @auth
                <a href="{{ route('account.profile') }}" class="block text-sm nav-link py-1">Mon profil</a>
                <a href="{{ route('account.orders') }}" class="block text-sm nav-link py-1">Mes commandes</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 py-1">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-sm nav-link py-1">Connexion</a>
                <a href="{{ route('register') }}" class="block text-sm nav-link py-1">Inscription</a>
            @endauth
        </div>
    </div>

</header>

{{-- ══════════════════════════════════════════════════════
     STYLES
══════════════════════════════════════════════════════ --}}
<style>
    /* ── Hover doré sur tous les liens nav ── */
    .nav-link {
        color: inherit;
        transition: color 0.2s ease;
        text-decoration: none;
    }
    .nav-link:hover { color: #9D8E1C !important; }

    /* ── Icônes boutons ── */
    .icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        color: #374151;
        transition: color 0.2s ease, background-color 0.2s ease;
        cursor: pointer;
        position: relative;
        text-decoration: none;
    }
    .icon-btn svg {
        width: 20px;
        height: 20px;
        flex-shrink: 0;
    }
    .icon-btn:hover {
        color: #9D8E1C;
        background-color: #fdf8e1;
    }

    /* ── Badge panier ── */
    .cart-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #1a1a0e;
        color: white;
        font-size: 9px;
        font-weight: 700;
        min-width: 16px;
        height: 16px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 3px;
        line-height: 1;
    }

    /* ── Barre de recherche animée ── */
    .search-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
    }
    .search-ring {
        position: absolute;
        inset: -2.5px;
        border-radius: 9999px;
        background: conic-gradient(
            from var(--angle, 0deg),
            transparent 0deg, transparent 50deg,
            #C8A800 80deg, #F0D000 110deg,
            #9D8E1C 140deg, #584F05 175deg,
            #978607 205deg, #D4B800 235deg,
            transparent 265deg, transparent 360deg
        );
        opacity: 0;
        transition: opacity 0.35s ease;
        pointer-events: none;
        z-index: 0;
        animation: spin-border 2.4s linear infinite;
        animation-play-state: paused;
    }
    .search-ring::after {
        content: '';
        position: absolute;
        inset: 2.5px;
        border-radius: 9999px;
        background: #f3f4f6;
    }
    .search-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #f3f4f6;
        border-radius: 9999px;
        padding: 8px 16px;
        width: 18rem;
        cursor: text;
    }
    .search-inner input {
        background: transparent;
        border: none;
        outline: none;
        font-size: 0.8rem;
        width: 100%;
        color: inherit;
        font-family: inherit;
        letter-spacing: 0.02em;
    }
    .search-inner input::placeholder { color: #9ca3af; }
    .search-wrapper.is-focused .search-ring {
        opacity: 1;
        animation-play-state: running;
    }

    @property --angle {
        syntax: '<angle>';
        initial-value: 0deg;
        inherits: false;
    }
    @keyframes spin-border {
        from { --angle: 0deg; }
        to   { --angle: 360deg; }
    }

    /* ── Dropdown menu custom ── */
    .dropdown-content a:hover,
    .dropdown-content li > *:hover {
        color: #9D8E1C !important;
        background-color: #fdf8e1 !important;
        border-radius: 6px;
    }
</style>

<script>
    (function () {
        const input   = document.getElementById('main-search');
        const wrapper = document.getElementById('search-wrapper');
        if (!input || !wrapper) return;
        input.addEventListener('focus',  () => wrapper.classList.add('is-focused'));
        input.addEventListener('blur',   () => wrapper.classList.remove('is-focused'));
    })();

    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu?.classList.toggle('hidden');
    }

    // Countdown timer
    (function () {
        const el = document.getElementById('navbar-countdown');
        if (!el) return;
        let [h, m, s] = el.textContent.split(':').map(Number);
        setInterval(() => {
            if (--s < 0) { s = 59; if (--m < 0) { m = 59; if (--h < 0) h = 0; } }
            el.textContent = [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
        }, 1000);
    })();
</script>
