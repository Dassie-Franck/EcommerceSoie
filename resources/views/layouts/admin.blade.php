<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | AfriSoie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap');

        :root {
            --gold: #9D8E1C;
            --gold-dark: #584F05;
            --gold-light: #D4B800;
            --sidebar-w: 260px;
        }

        body { font-family: 'Outfit', sans-serif; background: #f4f4f0; }

        /* ── SIDEBAR ─────────────────────────────────────────── */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: #ffffff;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: transform 0.3s ease;
        }
        .admin-sidebar .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--gold-light);
            letter-spacing: 0.02em;
        }
        .admin-sidebar .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 8px;
            color: rgba(0, 0, 0, 0.6);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .admin-sidebar .nav-item:hover {
            background: rgba(157,142,28,0.12);
            color: var(--gold-light);
        }
        .admin-sidebar .nav-item.active {
            background: linear-gradient(90deg, rgba(157,142,28,0.25), rgba(157,142,28,0.08));
            color: var(--gold-light);
            border-left: 2px solid var(--gold-light);
        }
        .admin-sidebar .nav-item svg {
            width: 18px; height: 18px; flex-shrink: 0;
        }
        .nav-badge {
            margin-left: auto;
            background: #C8102E;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 999px;
        }

        /* ── MAIN CONTENT ────────────────────────────────────── */
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        /* ── TOPBAR ──────────────────────────────────────────── */
        .admin-topbar {
            background: white;
            border-bottom: 1px solid #e8e8e4;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        /* ── CARDS ───────────────────────────────────────────── */
        .admin-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e8e8e4;
            overflow: hidden;
        }
        .admin-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f0ec;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a0e;
            letter-spacing: 0.01em;
        }

        /* ── STAT CARDS ──────────────────────────────────────── */
        .stat-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e8e8e4;
            padding: 20px;
        }
        .stat-card .stat-label {
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #888;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0f0f0a;
            margin: 4px 0 2px;
            line-height: 1;
        }
        .stat-card .stat-sub {
            font-size: 0.72rem;
            color: #888;
        }
        .stat-card .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── TABLE ───────────────────────────────────────────── */
        .admin-table { width: 100%; border-collapse: collapse; }
        .admin-table th {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #888;
            padding: 10px 16px;
            text-align: left;
            background: #fafaf8;
            border-bottom: 1px solid #f0f0ec;
        }
        .admin-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f5f5f3;
            font-size: 0.875rem;
            color: #1a1a0e;
            vertical-align: middle;
        }
        .admin-table tr:last-child td { border-bottom: none; }
        .admin-table tr:hover td { background: #fafaf8; }

        /* ── BADGE STATUS ────────────────────────────────────── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        .status-badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: currentColor;
        }
        .status-active   { background: #f0fdf4; color: #16a34a; }
        .status-inactive { background: #fef2f2; color: #dc2626; }
        .status-pending  { background: #fffbeb; color: #d97706; }
        .status-processing { background: #eff6ff; color: #2563eb; }
        .status-shipped  { background: #f0fdf4; color: #16a34a; }
        .status-completed{ background: #f0fdf4; color: #15803d; }
        .status-cancelled{ background: #fef2f2; color: #dc2626; }
        .status-warning  { background: #fffbeb; color: #d97706; }

        /* ── BUTTONS ─────────────────────────────────────────── */
        .btn-gold {
            background: linear-gradient(90deg, var(--gold), var(--gold-dark));
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.2s;
            text-decoration: none;
        }
        .btn-gold:hover { opacity: 0.9; color: white; }
        .btn-outline-sm {
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            background: white;
            color: #444;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-outline-sm:hover { border-color: var(--gold); color: var(--gold); background: #fdf8e1; }
        .btn-danger-sm {
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid #fecaca;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            background: white;
            color: #dc2626;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s;
        }
        .btn-danger-sm:hover { background: #fef2f2; border-color: #dc2626; }

        /* ── MODALS ──────────────────────────────────────────── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }
        .modal-box {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            transform: scale(0.95);
            transition: transform 0.2s;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0f0ec;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }
        .modal-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f0f0a;
        }
        .modal-close {
            width: 32px; height: 32px;
            border-radius: 8px;
            border: 1px solid #e8e8e4;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .modal-close:hover { background: #f5f5f3; }
        .modal-body { padding: 20px 24px; }
        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #f0f0ec;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            position: sticky;
            bottom: 0;
            background: white;
        }

        /* ── FORM CONTROLS ───────────────────────────────────── */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e8e8e4;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: 'Outfit', sans-serif;
            transition: border-color 0.2s;
            background: white;
            color: #1a1a0e;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(157,142,28,0.1);
        }
        .form-select {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e8e8e4;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: 'Outfit', sans-serif;
            background: white;
            color: #1a1a0e;
            cursor: pointer;
        }
        .form-select:focus {
            outline: none;
            border-color: var(--gold);
        }
        .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e8e8e4;
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: 'Outfit', sans-serif;
            resize: vertical;
            min-height: 90px;
        }
        .form-textarea:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(157,142,28,0.1);
        }

        /* ── TOGGLE SWITCH ───────────────────────────────────── */
        .toggle-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .toggle-switch {
            position: relative;
            width: 40px; height: 22px;
            flex-shrink: 0;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            inset: 0;
            background: #ddd;
            border-radius: 999px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .toggle-slider::before {
            content: '';
            position: absolute;
            width: 16px; height: 16px;
            left: 3px; top: 3px;
            background: white;
            border-radius: 50%;
            transition: transform 0.2s;
        }
        .toggle-switch input:checked + .toggle-slider { background: var(--gold); }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(18px); }

        /* ── IMAGE UPLOAD ────────────────────────────────────── */
        .upload-zone {
            border: 2px dashed #e8e8e4;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafaf8;
        }
        .upload-zone:hover { border-color: var(--gold); background: #fdf8e1; }
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }
        .image-preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e8e8e4;
        }
        .image-preview-item img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .image-preview-item .remove-img {
            position: absolute;
            top: 3px; right: 3px;
            width: 20px; height: 20px;
            background: rgba(220,38,38,0.9);
            border-radius: 50%;
            border: none;
            color: white;
            font-size: 0.6rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── VARIANT ROWS ────────────────────────────────────── */
        .variant-row {
            display: grid;
            grid-template-columns: 1fr 1fr 80px 80px 80px 36px;
            gap: 8px;
            align-items: center;
            padding: 8px;
            background: #fafaf8;
            border-radius: 8px;
            border: 1px solid #f0f0ec;
            margin-bottom: 6px;
        }

        /* ── SEARCH BAR ──────────────────────────────────────── */
        .search-bar {
            position: relative;
        }
        .search-bar svg {
            position: absolute;
            left: 10px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: #888;
            pointer-events: none;
        }
        .search-bar input {
            padding-left: 34px;
            width: 240px;
        }

        /* ── RESPONSIVE MOBILE ───────────────────────────────── */
        @media (max-width: 1024px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
            }
        }
        @media (max-width: 640px) {
            .admin-topbar { padding: 10px 16px; }
            main.admin-content { padding: 16px; }
            .variant-row {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
<aside class="admin-sidebar" id="adminSidebar">

    {{-- Logo --}}
    <div style="padding: 24px 20px 20px; border-bottom: 1px solid rgb(250, 245, 245);">
        <div class="logo"><a href="{{ route('shop.home') }}">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="AfriSoie"
                         class="h-40 w-auto object-contain">
                </a></div>
        <p style="font-size:0.7rem; color:rgba(255,255,255,0.3); margin-top:4px; letter-spacing:0.1em; text-transform:uppercase;">
            Administration
        </p>
    </div>

    {{-- Navigation --}}
    <nav style="padding: 16px 12px; flex: 1; overflow-y: auto;">

        <p style="font-size:0.62rem; color:rgba(255,255,255,0.25); letter-spacing:0.15em; text-transform:uppercase; padding: 0 8px; margin-bottom:8px;">
            Menu Principal
        </p>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.products.index') }}"
           class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Produits
        </a>

        <a href="{{ route('admin.categories.index') }}"
           class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            Catégories
        </a>

        <a href="{{ route('admin.orders.index') }}"
           class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Commandes
        </a>

        <a href="{{ route('admin.reviews.index') }}"
           class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Avis clients
        </a>

        <div style="height:1px; background:rgba(255,255,255,0.06); margin: 16px 8px;"></div>

        <a href="{{ route('shop.home') }}" target="_blank"
           class="nav-item">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            Voir le site
        </a>

    </nav>

    {{-- User + Logout --}}
    <div style="padding: 16px 12px; border-top: 1px solid rgba(255,255,255,0.06);">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px; padding:10px; background:rgba(255,255,255,0.05); border-radius:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#9D8E1C,#584F05);display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:700;color:white;flex-shrink:0;">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div style="overflow:hidden;">
                <p style="font-size:0.8rem;font-weight:600;color:rgba(255,255,255,0.9);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ auth()->user()->name ?? 'Admin' }}
                </p>
                <p style="font-size:0.65rem;color:rgba(255,255,255,0.35);">Administrateur</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width:100%;padding:8px;border-radius:8px;background:rgba(220,38,38,0.1);border:1px solid rgba(220,38,38,0.2);color:#f87171;font-size:0.78rem;font-weight:500;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all 0.2s;"
                    onmouseover="this.style.background='rgba(220,38,38,0.2)'"
                    onmouseout="this.style.background='rgba(220,38,38,0.1)'">
                <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnexion
            </button>
        </form>
    </div>

</aside>

{{-- ── OVERLAY MOBILE ───────────────────────────────────────── --}}
<div id="sidebarOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:49;"
     onclick="toggleSidebar()"></div>

{{-- ── MAIN ─────────────────────────────────────────────────── --}}
<div class="admin-main">

    {{-- Topbar --}}
    <div class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            {{-- Hamburger mobile --}}
            <button onclick="toggleSidebar()"
                    style="display:none;width:36px;height:36px;border-radius:8px;border:1px solid #e8e8e4;background:white;cursor:pointer;align-items:center;justify-content:center;"
                    id="hamburgerBtn">
                <svg style="width:18px;height:18px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <p style="font-size:0.75rem;color:#888;letter-spacing:0.05em;text-transform:uppercase;">
                    @yield('breadcrumb', 'Dashboard')
                </p>
                <p style="font-size:1rem;font-weight:700;color:#0f0f0a;line-height:1.2;">
                    @yield('page-title', 'Tableau de bord')
                </p>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
            {{-- Notif --}}
            <button style="width:36px;height:36px;border-radius:8px;border:1px solid #e8e8e4;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;position:relative;">
                <svg style="width:18px;height:18px;color:#444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Content --}}
    <main class="admin-content" style="padding: 24px;">
        {{-- Flash messages --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:#15803d;">
                <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:0.875rem;color:#dc2626;">
                <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

</div>

@stack('scripts')

<script>
    // Sidebar mobile toggle
    function toggleSidebar() {
        const sidebar  = document.getElementById('adminSidebar');
        const overlay  = document.getElementById('sidebarOverlay');
        const isOpen   = sidebar.classList.contains('open');
        sidebar.classList.toggle('open');
        overlay.style.display = isOpen ? 'none' : 'block';
    }

    // Show hamburger on mobile
    function checkMobile() {
        const btn = document.getElementById('hamburgerBtn');
        if (window.innerWidth < 1024) {
            btn.style.display = 'flex';
        } else {
            btn.style.display = 'none';
            document.getElementById('adminSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').style.display = 'none';
        }
    }
    checkMobile();
    window.addEventListener('resize', checkMobile);

    // Modal helpers
    function openModal(id) {
        const m = document.getElementById(id);
        if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
    }
    function closeModal(id) {
        const m = document.getElementById(id);
        if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
    }
    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', e => {
            if (e.target === m) closeModal(m.id);
        });
    });
</script>

</body>
</html>
