@extends('layouts.app')
@section('title', 'Mes adresses')
@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mes adresses</h1>
    <div class="space-y-4 mb-8">
        @forelse($addresses as $address)
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body flex-row items-center justify-between">
                    <div>
                        <p class="font-medium">{{ $address->full_name }}</p>
                        <p class="text-sm text-base-content/60">{{ $address->street }}, {{ $address->city }} {{ $address->postal_code }}, {{ $address->country }}</p>
                    </div>
                    <form method="POST" action="{{ route('account.addresses.destroy', $address) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs text-error">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-base-content/60">Aucune adresse enregistrée.</p>
        @endforelse
    </div>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-lg font-heading">Ajouter une adresse</h2>
            <form method="POST" action="{{ route('account.addresses.store') }}" class="grid grid-cols-2 gap-4">
                @csrf
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Nom complet</span></label><input type="text" name="full_name" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Téléphone</span></label><input type="text" name="phone" class="input input-bordered" required></div>
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Rue</span></label><input type="text" name="street" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Ville</span></label><input type="text" name="city" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Code postal</span></label><input type="text" name="postal_code" class="input input-bordered" required></div>
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Pays</span></label><input type="text" name="country" class="input input-bordered" required></div>
                <button type="submit" class="btn btn-primary col-span-2">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection