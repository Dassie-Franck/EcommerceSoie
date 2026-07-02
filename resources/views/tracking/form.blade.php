@extends('layouts.app')

@section('title', 'Suivi de colis')

@section('content')
<div class="container mx-auto px-4 py-12 max-w-2xl">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Suivi de votre colis</h1>
        <p class="text-gray-600">Entrez les informations de votre commande</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8">

        @if(Auth::check())
            {{-- Utilisateur connecté --}}
            <div class="text-center">
                <div class="mb-4">
                    <i class="fas fa-user-check text-amber-600 text-5xl mb-3"></i>
                    <p class="text-gray-700 mb-4">Vous êtes connecté(e) en tant que {{ Auth::user()->name }}</p>
                </div>
                <a href="{{ route('account.tracking.index') }}" class="bg-gradient-to-r from-amber-600 to-amber-700 text-white px-6 py-3 rounded-lg font-semibold inline-flex items-center gap-2">
                    <i class="fas fa-truck"></i> Mes colis en cours
                </a>
            </div>
        @else
            {{-- Utilisateur invité --}}
            <form method="POST" action="{{ route('tracking.lookup') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Numéro de commande</label>
                    <input type="text" name="order_reference"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500"
                           placeholder="Ex: EC-20241215-1234"
                           value="{{ old('order_reference') }}"
                           required>
                    <p class="text-xs text-gray-500 mt-1">Le numéro reçu dans votre email de confirmation</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email de la commande</label>
                    <input type="email" name="email"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:border-amber-500 focus:ring-1 focus:ring-amber-500"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 text-white py-3 rounded-lg font-semibold hover:opacity-90 transition inline-flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Suivre mon colis
                </button>
            </form>

            <div class="mt-6 pt-6 border-t text-center">
                <p class="text-sm text-gray-500">
                    Vous avez un compte ?
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-medium">
                        Connectez-vous
                    </a>
                </p>
            </div>
        @endif

    </div>
</div>
@endsection
