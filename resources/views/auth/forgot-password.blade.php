@extends('layouts.guest')
@section('title', 'Mot de passe oublié')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-2">Mot de passe oublié</h2>
        <p class="text-sm text-base-content/60 text-center mb-6">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" required autofocus>
                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-full">Envoyer le lien</button>
        </form>
        <p class="text-center text-sm mt-4"><a href="{{ route('login') }}" class="link link-primary">Retour à la connexion</a></p>
    </div>
</div>
@endsection