@extends('layouts.admin')

@section('title', 'Commande #' . $order->reference)

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Commande #{{ $order->reference }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-800">← Retour</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne gauche --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Infos client --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Informations client</h2>
                <p><strong>Nom :</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong>Email :</strong> {{ $order->user->email ?? $order->email ?? 'N/A' }}</p>
                <p><strong>Téléphone :</strong> {{ $order->phone ?? 'Non renseigné' }}</p>
                <p><strong>Adresse :</strong> {{ $order->address ?? 'Non renseignée' }}</p>
            </div>

            {{-- Articles --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Articles commandés</h2>
                <table class="w-full">
                    <thead class="border-b">
                        <tr>
                            <th class="text-left py-2">Produit</th>
                            <th class="text-center py-2">Qté</th>
                            <th class="text-right py-2">Prix unitaire</th>
                            <th class="text-right py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="border-b">
                            <td class="py-2">{{ $item->product_name ?? $item->product->name }}</td>
                            <td class="text-center py-2">{{ $item->quantity }}</td>
                            <td class="text-right py-2">{{ number_format($item->price, 0, ',', ' ') }} €</td>
                            <td class="text-right py-2">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right py-2 font-semibold">Total :</td>
                            <td class="text-right py-2 font-bold">{{ number_format($order->total, 0, ',', ' ') }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Colonne droite --}}
        <div class="space-y-6">

            {{-- Statut --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Statut</h2>
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full border rounded-lg px-3 py-2 mb-3">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                    <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded-lg">Mettre à jour</button>
                </form>
            </div>

            {{-- Suivi colis --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Suivi du colis</h2>

                <form method="POST" action="{{ route('admin.orders.tracking', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Numéro de suivi DHL</label>
                        <input type="text" name="tracking_number"
                               class="w-full border rounded-lg px-3 py-2"
                               value="{{ $order->tracking_number ?? '' }}"
                               placeholder="Ex: 8564385550">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Transporteur</label>
                        <select name="shipping_carrier" class="w-full border rounded-lg px-3 py-2">
                            <option value="">Sélectionner</option>
                            <option value="dhl" {{ ($order->shipping_carrier ?? '') == 'dhl' ? 'selected' : '' }}>DHL Express</option>
                            <option value="dhl_ecommerce" {{ ($order->shipping_carrier ?? '') == 'dhl_ecommerce' ? 'selected' : '' }}>DHL eCommerce</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded-lg">Enregistrer</button>
                </form>

                @if($trackingInfo)
                <div class="mt-4 pt-4 border-t">
                    <p class="font-medium mb-2">Statut actuel :
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $trackingInfo['status'] == 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $trackingInfo['status'] == 'in_transit' ? 'bg-blue-100 text-blue-700' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $trackingInfo['status'])) }}
                        </span>
                    </p>
                    @foreach($trackingInfo['events'] as $event)
                    <div class="text-sm text-gray-600 py-1">
                        {{ \Carbon\Carbon::parse($event['timestamp'])->format('d/m/Y H:i') }} - {{ $event['description'] }}
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
