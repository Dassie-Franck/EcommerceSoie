@extends('layouts.account')
@section('title', 'Mes Commandes')

@section('account-content')

<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Header --}}
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#1a1a0e;">
            Mes Commandes
        </h1>
        <p style="font-size:0.875rem;color:#888;margin-top:4px;">
            {{ $orders->total() }} commande{{ $orders->total() > 1 ? 's' : '' }} au total
        </p>
    </div>

    @forelse($orders as $order)

        <div class="account-card">

            {{-- Header commande --}}
            <div class="account-card-header" style="flex-wrap:wrap;gap:8px;">
                <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
                    <div>
                        <p style="font-size:0.7rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;">
                            Commande
                        </p>
                        <p style="font-weight:700;font-size:0.875rem;font-family:monospace;color:#1a1a0e;">
                            {{ $order->order_number }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size:0.7rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;">
                            Date
                        </p>
                        <p style="font-weight:500;font-size:0.875rem;color:#1a1a0e;">
                            {{ $order->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size:0.7rem;color:#888;text-transform:uppercase;letter-spacing:0.08em;">
                            Total
                        </p>
                        <p style="font-weight:700;font-size:0.875rem;color:#9D8E1C;">
                            {{ number_format($order->total, 2) }} €
                        </p>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="order-status status-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending')    En attente @break
                            @case('processing') En traitement @break
                            @case('shipped')    Expédié @break
                            @case('completed')  Livré @break
                            @case('cancelled')  Annulé @break
                            @default {{ $order->status }}
                        @endswitch
                    </span>

                    {{-- Bouton voir détail --}}
                    <a href="{{ route('account.orders.show', $order) }}"
                       style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border:1.5px solid #e8e8e4;border-radius:6px;font-size:0.75rem;font-weight:500;color:#444;text-decoration:none;transition:all 0.2s;"
                       onmouseover="this.style.borderColor='#9D8E1C';this.style.color='#9D8E1C';"
                       onmouseout="this.style.borderColor='#e8e8e4';this.style.color='#444';">
                        <svg style="width:13px;height:13px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Voir
                    </a>

                    {{-- Annuler si pending --}}
                    @if($order->status === 'pending')
                        <form method="POST" action="{{ route('account.orders.cancel', $order) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    onclick="return confirm('Annuler cette commande ?')"
                                    style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border:1.5px solid #fecaca;border-radius:6px;font-size:0.75rem;font-weight:500;color:#dc2626;background:white;cursor:pointer;transition:all 0.2s;"
                                    onmouseover="this.style.background='#fef2f2';"
                                    onmouseout="this.style.background='white';">
                                Annuler
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Articles --}}
            <div style="padding:16px 20px;">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($order->items->take(4) as $item)
                        <div style="display:flex;align-items:center;gap:8px;background:#f8f8f6;padding:8px 12px;border-radius:8px;border:1px solid #f0f0ec;">
                            <p style="font-size:0.78rem;font-weight:500;color:#1a1a0e;">
                                {{ $item->product_name }}
                            </p>
                            @if($item->variant_label)
                                <span style="font-size:0.68rem;color:#888;background:white;padding:1px 6px;border-radius:999px;border:1px solid #e8e8e4;">
                                    {{ $item->variant_label }}
                                </span>
                            @endif
                            <span style="font-size:0.72rem;color:#888;">× {{ $item->quantity }}</span>
                        </div>
                    @endforeach
                    @if($order->items->count() > 4)
                        <div style="display:flex;align-items:center;padding:8px 12px;background:#f8f8f6;border-radius:8px;border:1px solid #f0f0ec;">
                            <span style="font-size:0.78rem;color:#888;">
                                +{{ $order->items->count() - 4 }} article(s)
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Récap prix --}}
                <div style="display:flex;gap:16px;margin-top:12px;padding-top:12px;border-top:1px solid #f0f0ec;font-size:0.78rem;color:#888;flex-wrap:wrap;">
                    <span>Sous-total : <strong style="color:#1a1a0e;">{{ number_format($order->subtotal, 2) }} €</strong></span>
                    <span>Livraison : <strong style="color:#1a1a0e;">
                        {{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2) . ' €' : 'Gratuite' }}
                    </strong></span>
                    @if($order->discount > 0)
                        <span>Réduction : <strong style="color:#16a34a;">-{{ number_format($order->discount, 2) }} €</strong></span>
                    @endif
                </div>
            </div>

        </div> 

    @empty
        <div class="account-card" style="padding:60px 20px;text-align:center;">
            <svg style="width:48px;height:48px;color:#ddd;margin:0 auto 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p style="font-size:1rem;font-weight:600;color:#1a1a0e;margin-bottom:8px;">Aucune commande</p>
            <p style="font-size:0.875rem;color:#888;margin-bottom:20px;">
                Vous n'avez pas encore passé de commande.
            </p>
            <a href="{{ route('shop.catalogue') }}"
               class="btn-gold-account" style="text-decoration:none;">
                Découvrir nos collections
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div>{{ $orders->links() }}</div>
    @endif

</div>

@endsection
