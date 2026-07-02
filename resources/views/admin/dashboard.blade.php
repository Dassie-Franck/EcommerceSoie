@extends('layouts.admin')
@section('title', 'Tableau de bord')

@push('head')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#9D8E1C;--gold-dark:#584F05;--gold-mid:#978607;--gold-light:#C8B84A;--gold-bg:#F5F2E8;--white:#fff;--cream:#FAFAF7;--ink:#1C1C1A;--ink-soft:#3A3A38;--muted:#7A7870;--border:#E5E1D8;--border-gold:#C8B84A55;}
body{font-family:'Jost',sans-serif;}
.font-display{font-family:'Cormorant Garamond',serif;}
.stat-card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:1.5rem;transition:box-shadow .2s,border-color .2s;}
.stat-card:hover{border-color:var(--border-gold);box-shadow:0 6px 24px #9D8E1C0D;}
.stat-icon{width:44px;height:44px;border-radius:12px;background:var(--gold-bg);display:flex;align-items:center;justify-content:center;}
.btn-gold{background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:#fff;border:none;letter-spacing:.06em;transition:opacity .2s,transform .15s;}
.btn-gold:hover{opacity:.88;transform:translateY(-1px);}
.status-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:.7rem;font-weight:600;letter-spacing:.05em;text-transform:uppercase;}
.status-pending{background:#FFF8E6;color:#92400E;}
.status-processing{background:#EFF6FF;color:#1E40AF;}
.status-shipped{background:#F0FDF4;color:#166534;}
.status-completed{background:#F5F3FF;color:#6D28D9;}
.status-cancelled{background:#FFF1F2;color:#9F1239;}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
.anim{animation:fadeUp .45s ease both;}
.anim-1{animation-delay:.05s}.anim-2{animation-delay:.1s}.anim-3{animation-delay:.15s}.anim-4{animation-delay:.2s}.anim-5{animation-delay:.25s}
</style>
@endpush

@section('content')
<div style="background:var(--cream);min-height:100vh;padding:2rem;">

    {{-- En-tête --}}
    <div class="flex items-center justify-between mb-8 anim anim-1">
        <div>
            <h1 class="font-display text-3xl font-semibold" style="color:var(--ink);">Tableau de bord</h1>
            <p class="text-sm mt-1" style="color:var(--muted);">
                {{ now()->isoFormat('dddd D MMMM YYYY') }}
            </p>
        </div>
        <a href="{{ route('admin.products.index') }}"
           class="btn-gold flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium uppercase tracking-widest">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Nouveau produit
        </a>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        @php
            $kpis = [
                ['icon'=>'shopping-bag',  'label'=>'Commandes',        'value'=> number_format($stats['total_orders']),                    'sub'=>'total'],
                ['icon'=>'banknote',      'label'=>"Chiffre d'affaires",'value'=> number_format($stats['total_revenue'],0,',',' ').' €', 'sub'=>'revenus'],
                ['icon'=>'package',       'label'=>'Produits',          'value'=> number_format($stats['total_products']),                  'sub'=>'références'],
                ['icon'=>'users',         'label'=>'Clients',           'value'=> number_format($stats['total_customers']),                 'sub'=>'inscrits'],
            ];
        @endphp

        @foreach($kpis as $i => $kpi)
        <div class="stat-card anim anim-{{ $i+1 }}">
            <div class="stat-icon mb-3">
                <i data-lucide="{{ $kpi['icon'] }}" class="w-5 h-5" style="color:var(--gold);"></i>
            </div>
            <p class="text-xs font-medium uppercase tracking-widest mb-1" style="color:var(--muted);">
                {{ $kpi['label'] }}
            </p>
            <p class="font-display text-3xl font-semibold" style="color:var(--ink);">
                {{ $kpi['value'] }}
            </p>
            <p class="text-xs mt-1" style="color:var(--muted);">{{ $kpi['sub'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Commandes récentes --}}
    <div class="anim anim-5" style="background:var(--white);border:1px solid var(--border);border-radius:16px;overflow:hidden;">

        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid var(--border);">
            <h2 class="font-display text-xl font-semibold" style="color:var(--ink);">Commandes récentes</h2>
            <a href="{{ route('admin.orders.index') }}"
               class="text-xs font-medium uppercase tracking-widest flex items-center gap-1 transition-colors hover:opacity-70"
               style="color:var(--gold);">
                Voir tout <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background:var(--cream);border-bottom:1px solid var(--border);">
                        <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-widest" style="color:var(--muted);">N° commande</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-widest" style="color:var(--muted);">Client</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-widest" style="color:var(--muted);">Total</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold uppercase tracking-widest" style="color:var(--muted);">Statut</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:var(--border);">
                    @foreach($stats['recent_orders'] as $order)
                    <tr class="hover:bg-[var(--cream)] transition-colors">
                        <td class="px-6 py-4 font-mono text-xs font-medium" style="color:var(--ink);">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 text-sm" style="color:var(--ink-soft);">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold" style="color:var(--gold);">
                            {{ number_format($order->total, 0, ',', ' ') }} €
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $cls = match($order->status) {
                                    'pending'    => 'status-pending',
                                    'processing' => 'status-processing',
                                    'shipped'    => 'status-shipped',
                                    'completed'  => 'status-completed',
                                    default      => 'status-cancelled',
                                };
                                $labels = ['pending'=>'En attente','processing'=>'En cours','shipped'=>'Expédié','completed'=>'Terminé','cancelled'=>'Annulé'];
                            @endphp
                            <span class="status-badge {{ $cls }}">{{ $labels[$order->status] ?? $order->status }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="text-xs font-medium flex items-center gap-1 justify-end transition-colors hover:opacity-70"
                               style="color:var(--gold);">
                                Détails <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<script>document.addEventListener('DOMContentLoaded',()=>lucide.createIcons());</script>
@endsection
