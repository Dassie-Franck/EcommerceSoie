<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>@yield('title', 'Mon Compte') — AfriSoie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap');

        :root {
            --gold: #9D8E1C;
            --gold-dark: #584F05;
            --gold-light: #D4B800;
            --gold-pale: #fdf8e1;
            --border: #e8e8e4;
            --text-muted: #888880;
        }

        body { font-family: 'Outfit', sans-serif; background: #f8f8f6; }

        /* ── SIDEBAR NAV ──────────────────────────────────────── */
        .account-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #555;
            text-decoration: none;
            transition: all 0.2s;
        }
        .account-nav-link:hover {
            background: var(--gold-pale);
            color: var(--gold);
        }
        .account-nav-link.active {
            background: var(--gold-pale);
            color: var(--gold);
            font-weight: 600;
            border-left: 2px solid var(--gold);
        }
        .account-nav-link svg {
            width: 18px; height: 18px; flex-shrink: 0;
        }

        /* ── CARDS ───────────────────────────────────────────── */
        .account-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .account-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0ec;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .account-card-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #1a1a0e;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        /* ── STATUS BADGES ───────────────────────────────────── */
        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .order-status::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        .status-pending    { background: #fffbeb; color: #d97706; }
        .status-processing { background: #eff6ff; color: #2563eb; }
        .status-shipped    { background: #f0fdf4; color: #16a34a; }
        .status-completed  { background: #f0fdf4; color: #15803d; }
        .status-cancelled  { background: #fef2f2; color: #dc2626; }

        /* ── FORM INPUTS ─────────────────────────────────────── */
        .account-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s;
            background: white;
            color: #1a1a0e;
        }
        .account-input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(157,142,28,0.08);
        }
        .account-label {
            display: block;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 6px;
        }

        /* ── BUTTON ──────────────────────────────────────────── */
        .btn-gold-account {
            background: linear-gradient(90deg, var(--gold), var(--gold-dark));
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            cursor: pointer;
            transition: opacity 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-gold-account:hover { opacity: 0.88; }

        /* ── AVATAR ──────────────────────────────────────────── */
        .avatar-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
        }

        /* ── WISHLIST CARD ───────────────────────────────────── */
        .wishlist-product-card {
            position: relative;
            background: white;
            border-radius: 10px;
            border: 1px solid var(--border);
            overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .wishlist-product-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
        .wishlist-product-card img {
            width: 100%;
            aspect-ratio: 3/4;
            object-fit: cover;
            object-position: top;
        }

        /* ── RESPONSIVE ──────────────────────────────────────── */
        @media (max-width: 768px) {
            .account-sidebar { display: none; }
            .account-sidebar.mobile-open { display: block; }
            .account-mobile-nav {
                display: flex;
                overflow-x: auto;
                gap: 8px;
                padding-bottom: 4px;
                margin-bottom: 16px;
                scrollbar-width: none;
            }
            .account-mobile-nav::-webkit-scrollbar { display: none; }
            .account-mobile-pill {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 14px;
                border-radius: 999px;
                border: 1.5px solid var(--border);
                font-size: 0.78rem;
                font-weight: 500;
                white-space: nowrap;
                text-decoration: none;
                color: #555;
                transition: all 0.2s;
                flex-shrink: 0;
            }
            .account-mobile-pill.active,
            .account-mobile-pill:hover {
                background: var(--gold-pale);
                border-color: var(--gold);
                color: var(--gold);
            }
        }
        @media (min-width: 769px) {
            .account-mobile-nav { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Topbar + Navbar du site --}}
    @include('components.topbar')
    @include('components.navbar')

    <div style="max-width:1200px;margin:0 auto;padding:32px 16px;">

        {{-- Flash messages --}}
        @include('components.flash-message')

        {{-- Mobile nav pills --}}
        <div class="account-mobile-nav">
            <a href="{{ route('account.profile') }}"
               class="account-mobile-pill {{ request()->routeIs('account.profile*') ? 'active' : '' }}">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Profil
            </a>
            <a href="{{ route('account.orders') }}"
               class="account-mobile-pill {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Commandes
            </a>
            <a href="{{ route('account.wishlist') }}"
               class="account-mobile-pill {{ request()->routeIs('account.wishlist') ? 'active' : '' }}">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.682l1.318-1.364a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                </svg>
                Favoris
            </a>
            <a href="{{ route('account.addresses') }}"
               class="account-mobile-pill {{ request()->routeIs('account.addresses*') ? 'active' : '' }}">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                </svg>
                Adresses
            </a>
        </div>

        {{-- Layout 2 colonnes --}}
        <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;align-items:start;">

            {{-- ── SIDEBAR ─────────────────────────────────────── --}}
            <aside class="account-sidebar" style="position:sticky;top:90px;">

                {{-- User info --}}
                <div class="account-card" style="padding:20px;margin-bottom:12px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                 alt="{{ auth()->user()->name }}"
                                 style="width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid var(--gold-pale);">
                        @else
                            <div class="avatar-circle" style="width:56px;height:56px;font-size:1.2rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div style="overflow:hidden;">
                            <p style="font-weight:700;font-size:0.875rem;color:#1a1a0e;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ auth()->user()->name }}
                            </p>
                            <p style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Nav links --}}
                <div class="account-card" style="padding:8px;">
                    <a href="{{ route('account.profile') }}"
                       class="account-nav-link {{ request()->routeIs('account.profile*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M5.121 17.804A9 9 0 1118.88 17.804M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Mon profil
                    </a>
                    <a href="{{ route('account.orders') }}"
                       class="account-nav-link {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Mes commandes
                    </a>
                    <a href="{{ route('account.wishlist') }}"
                       class="account-nav-link {{ request()->routeIs('account.wishlist') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.682l1.318-1.364a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z"/>
                        </svg>
                        Mes favoris
                    </a>
                    <a href="{{ route('account.addresses') }}"
                       class="account-nav-link {{ request()->routeIs('account.addresses*') ? 'active' : '' }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        Mes adresses
                    </a>

                    <div style="height:1px;background:#f0f0ec;margin:8px 0;"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="account-nav-link"
                                style="width:100%;background:none;border:none;cursor:pointer;text-align:left;color:#dc2626;">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Déconnexion
                        </button>
                    </form>
                </div>

            </aside>

            {{-- ── CONTENU ──────────────────────────────────────── --}}
            <main>
                @yield('account-content')
            </main>

        </div>
    </div>

    @include('components.footer')
    @stack('scripts')
</body>
</html>
