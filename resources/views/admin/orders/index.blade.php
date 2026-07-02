{{-- ══════════════════════════════════════════════════════════════
     orders/index.blade.php
══════════════════════════════════════════════════════════════ --}}
@extends('layouts.admin')
@section('title', 'Commandes')

@push('head')
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--gold:#9D8E1C;--gold-dark:#584F05;--gold-mid:#978607;--gold-bg:#F5F2E8;--white:#fff;--cream:#FAFAF7;--ink:#1C1C1A;--ink-soft:#3A3A38;--muted:#7A7870;--border:#E5E1D8;--border-gold:#C8B84A55;}
body{font-family:'Jost',sans-serif;}
.font-display{font-family:'Cormorant Garamond',serif;}
.admin-table{width:100%;border-collapse:collapse;}
.admin-table thead tr{background:var(--cream);border-bottom:1px solid var(--border);}
.admin-table th{text-align:left;padding:.75rem 1.25rem;font-size:.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:var(--muted);}
.admin-table tbody tr{border-bottom:1px solid var(--border);transition:background .15s;}
.admin-table tbody tr:hover{background:var(--cream);}
.admin-table td{padding:.875rem 1.25rem;font-size:.875rem;color:var(--ink-soft);}
.admin-table tbody tr:last-child{border-bottom:none;}
.status-badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:99px;font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;}
.s-pending{background:#FFF8E6;color:#92400E;border:1px solid #FDE68A;}
.s-processing{background:#EFF6FF;color:#1E40AF;border:1px solid #BFDBFE;}
.s-shipped{background:#F0FDF4;color:#166534;border:1px solid #BBF7D0;}
.s-completed{background:#F5F3FF;color:#6D28D9;border:1px solid #DDD6FE;}
.s-cancelled{background:#FFF1F2;color:#9F1239;border:1px solid #FECDD3;}
.btn-gold-sm{background:linear-gradient(90deg,#9D8E1C,#584F05,#978607);color:#fff;border:none;padding:6px 14px;border-radius:8px;font-size:.75rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;cursor:pointer;transition:opacity .2s;}
.btn-gold-sm:hover{opacity:.88;}
@keyframes fadeUp{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}
.anim{animation:fadeUp .4s ease both;}
</style>
@endpush

@section('content')
<div style="background:var(--cream);min-height:100vh;padding:2rem;">

    <div class="flex items-center justify-between mb-6 anim">
        <div>
            <h1 class="font-display text-3xl font-semibold" style="color:var(--ink);">Commandes</h1>
            <p class="text-sm mt-0.5" style="color:var(--muted);">{{ $orders->total() }} commandes au total</p>
        </div>
    </div>

    <div class="anim" style="background:var(--white);border:1px solid var(--border);border-radius:16px;overflow:hidden;">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N° commande</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $cls = match($order->status){
                            'pending'=>'s-pending','processing'=>'s-processing',
                            'shipped'=>'s-shipped','completed'=>'s-completed',default=>'s-cancelled'};
                        $labels=['pending'=>'En attente','processing'=>'En cours','shipped'=>'Expédié','completed'=>'Terminé','cancelled'=>'Annulé'];
                    @endphp
                    <tr>
                        <td class="font-mono text-xs font-semibold" style="color:var(--ink);">
                            {{ $order->order_number }}
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-semibold flex-shrink-0"
                                     style="background:linear-gradient(135deg,var(--gold),var(--gold-dark));">
                                    {{ strtoupper(substr($order->user->name,0,1)) }}
                                </div>
                                {{ $order->user->name }}
                            </div>
                        </td>
                        <td class="font-semibold" style="color:var(--gold);">
                            {{ number_format($order->total, 0, ',', ' ') }} €
                        </td>
                        <td class="text-xs" style="color:var(--muted);">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td><span class="status-badge {{ $cls }}">{{ $labels[$order->status] ?? $order->status }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('admin.orders.show', $order) }}"
                               class="btn-gold-sm inline-flex items-center gap-1.5">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Voir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12" style="color:var(--muted);">
                            <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                            <p>Aucune commande pour le moment</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4" style="border-top:1px solid var(--border);">
            {{ $orders->links() }}
        </div>
    </div>

</div>
<script>document.addEventListener('DOMContentLoaded',()=>lucide.createIcons());</script>
@endsection
