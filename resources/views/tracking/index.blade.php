@extends('layouts.app')

@section('title', 'Suivi de colis')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-2xl">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Suivi de votre colis</h1>
        <p class="text-gray-600">Entrez votre numéro de suivi DHL</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <form method="POST" action="{{ route('tracking.track') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Numéro de suivi
                </label>
                <input type="text"
                       name="tracking_number"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500"
                       placeholder="Ex: 8564385550"
                       value="{{ old('tracking_number') }}"
                       required>
                @error('tracking_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-gold w-full justify-center">
                <i class="fas fa-search mr-2"></i> Suivre mon colis
            </button>
        </form>
    </div>

</div>
@endsection
