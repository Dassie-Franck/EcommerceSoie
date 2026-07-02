@extends('layouts.app')

@section('title', 'Suivi - Commande #' . $order->reference)

@section('content')
<div class="container mx-auto px-4 py-12 max-w-4xl">

    <div class="mb-6">
        <a href="{{ route('tracking.form') }}" class="text-amber-600 hover:text-amber-700">
            <i class="fas fa-arrow-left mr-1"></i> Nouvelle recherche
        </a>
    </div>

    @if(!$trackingInfo)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-4xl mb-3"></i>
            <p class="text-gray-700">Les informations de suivi ne sont pas disponibles.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            {{-- En-tête --}}
            <div class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-5">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <p class="text-sm opacity-90">Commande</p>
                        <p class="text-xl font-bold">#{{ $order->reference }}</p>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Numéro de suivi</p>
                        <p class="text-xl font-mono font-bold">{{ $trackingInfo['tracking_number'] }}</p>
                    </div>
                    <div>
                        @php
                            $statusLabels = [
                                'delivered' => ['label' => 'Livré', 'color' => 'bg-green-500'],
                                'in_transit' => ['label' => 'En transit', 'color' => 'bg-blue-500'],
                                'pending' => ['label' => 'En attente', 'color' => 'bg-yellow-500'],
                                'unknown' => ['label' => 'Statut inconnu', 'color' => 'bg-gray-500']
                            ];
                            $status = $statusLabels[$trackingInfo['status']] ?? $statusLabels['unknown'];
                        @endphp
                        <span class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold {{ $status['color'] }}">
                            {{ $status['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Barre de progression --}}
            <div class="px-6 pt-6">
                @php
                    $progressMap = ['delivered' => 100, 'in_transit' => 60, 'pending' => 20, 'unknown' => 0];
                    $progress = $progressMap[$trackingInfo['status']] ?? 0;
                @endphp
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-amber-500 to-amber-600 transition-all duration-500" style="width: {{ $progress }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span>Commande validée</span>
                    <span>Expédiée</span>
                    <span>Livrée</span>
                </div>
            </div>

            {{-- Informations --}}
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 border-b border-gray-100 bg-gray-50">
                <div>
                    <p class="text-xs uppercase text-gray-400 mb-1">Transporteur</p>
                    <p class="font-medium">{{ strtoupper(str_replace('_', ' ', $order->shipping_carrier ?? 'DHL')) }}</p>
                </div>
                @if($trackingInfo['estimated_delivery'])
                <div>
                    <p class="text-xs uppercase text-gray-400 mb-1">Livraison estimée</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($trackingInfo['estimated_delivery'])->format('d/m/Y') }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs uppercase text-gray-400 mb-1">Dernière mise à jour</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($trackingInfo['last_updated'])->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            {{-- Historique --}}
            <div class="p-6">
                <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-history text-amber-600"></i> Historique
                </h3>
                <div class="space-y-4">
                    @forelse($trackingInfo['events'] as $event)
                    <div class="flex gap-4 pb-4 border-b border-gray-100 last:border-0">
                        <div class="w-32 text-sm text-gray-500">
                            {{ isset($event['timestamp']) ? \Carbon\Carbon::parse($event['timestamp'])->format('d/m/Y H:i') : '-' }}
                        </div>
                        <div class="flex-1">
                            <p class="font-medium">{{ $event['description'] ?? 'Mise à jour' }}</p>
                            @if($event['location'])
                                <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt text-amber-500 mr-1"></i> {{ $event['location'] }}</p>
                            @endif
                        </div>
                    </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucun événement disponible</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
