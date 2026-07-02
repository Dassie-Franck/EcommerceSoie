@extends('layouts.app')

@section('title', 'Mes colis')

@section('content')
<div class="container mx-auto px-4 py-12">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Mes colis en cours</h1>
            <p class="text-gray-600 text-sm mt-1">Suivez l'avancement de vos livraisons</p>
        </div>
        <a href="{{ route('account.orders.index') }}" class="text-amber-600 hover:text-amber-700">
            <i class="fas fa-arrow-left"></i> Mes commandes
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-semibold text-lg">#{{ $order->reference }}</span>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $order->status === 'processing' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500">Date : {{ $order->created_at->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">Total : {{ number_format($order->total, 0, ',', ' ') }} €</p>
                        <p class="text-xs font-mono text-gray-400 mt-1">N° suivi : {{ $order->tracking_number }}</p>
                    </div>
                    <div>
                        <a href="{{ route('account.tracking.order', $order) }}"
                           class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition inline-flex items-center gap-2">
                            <i class="fas fa-truck"></i> Suivre
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <i class="fas fa-box-open text-gray-300 text-6xl mb-4"></i>
            <p class="text-gray-500 mb-4">Aucun colis en cours de livraison.</p>
            <a href="{{ route('shop.catalogue') }}" class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-3 rounded-lg font-semibold inline-block">
                Découvrir nos produits
            </a>
        </div>
    @endif

</div>
@endsection
