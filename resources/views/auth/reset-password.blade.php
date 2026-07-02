@extends('layouts.guest')
@section('title', 'Nouveau mot de passe')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-2">
            Nouveau mot de passe
        </h2>
        <p class="text-sm text-base-content/60 text-center mb-6">
            Choisissez un nouveau mot de passe sécurisé.
        </p>

        @if($errors->any())
            <div class="alert alert-error mb-4">
                @foreach($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <div class="form-control">
                <label class="label"><span class="label-text">Nouveau mot de passe</span></label>
                <input type="password" name="password"
                       class="input input-bordered @error('password') input-error @enderror"
                       required autofocus>
                @error('password')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-base-content/50 mt-1">
                    Min. 8 caractères, majuscule, minuscule et chiffre requis.
                </p>
            </div>

            <div class="form-control">
                <label class="label"><span class="label-text">Confirmer le mot de passe</span></label>
                <input type="password" name="password_confirmation"
                       class="input input-bordered @error('password_confirmation') input-error @enderror"
                       required>
                @error('password_confirmation')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Réinitialiser le mot de passe
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            <a href="{{ route('login') }}" class="link link-primary">
                Retour à la connexion
            </a>
        </p>
    </div>
</div>
@endsection
