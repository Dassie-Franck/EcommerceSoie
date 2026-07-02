@extends('layouts.admin')

@section('title', 'Modifier la commande #' . $order->reference)

@section('content')

<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Commande #{{ $order->reference }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-800">
            ← Retour aux commandes
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Colonne gauche : Informations commande --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Informations client --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Informations client</h2>
                <p><strong>Nom :</strong> {{ $order->user->name ?? 'N/A' }}</p>
                <p><strong>Email :</strong> {{ $order->user->email ?? 'N/A' }}</p>
                <p><strong>Téléphone :</strong> {{ $order->phone ?? 'Non renseigné' }}</p>
                <p><strong>Adresse :</strong> {{ $order->address ?? 'Non renseignée' }}</p>
            </div>

            {{-- Détails de la commande --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Articles commandés</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Produit</th>
                            <th class="text-center py-2">Quantité</th>
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

        {{-- Colonne droite : Mise à jour commande --}}
        <div class="space-y-6">

            {{-- Statut commande --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Statut de la commande</h2>

                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    {{-- ========================================== --}}
                    {{-- CODE À INSÉRER ICI (formulaire d'expédition) --}}
                    {{-- ========================================== --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de suivi DHL</label>
                        <input type="text" name="tracking_number"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-amber-500 focus:ring-1 focus:ring-amber-500"
                               value="{{ old('tracking_number', $order->tracking_number ?? '') }}">
                        <p class="text-xs text-gray-500 mt-1">Exemple: 8564385550</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transporteur</label>
                        <select name="shipping_carrier" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
                            <option value="">Sélectionner un transporteur</option>
                            <option value="dhl" {{ ($order->shipping_carrier ?? '') == 'dhl' ? 'selected' : '' }}>DHL Express</option>
                            <option value="dhl_ecommerce" {{ ($order->shipping_carrier ?? '') == 'dhl_ecommerce' ? 'selected' : '' }}>DHL eCommerce</option>
                            <option value="colissimo" {{ ($order->shipping_carrier ?? '') == 'colissimo' ? 'selected' : '' }}>Colissimo (La Poste)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white py-2 rounded-lg font-semibold hover:opacity-90 transition">
                        Mettre à jour la commande
                    </button>
                </form>
            </div>

            {{-- Actions --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-4">Actions</h2>

                @if($order->tracking_number)
                <a href="{{ route('tracking.order', $order) }}" target="_blank"
                   class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition mb-3">
                    Suivre le colis
                </a>
                @endif

                <button type="button" onclick="confirmDelete()"
                        class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700 transition">
                    Supprimer la commande
                </button>
            </div>

        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if(confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>

<form id="delete-form" method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection
