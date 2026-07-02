@extends('layouts.guest')
@section('title', 'Vérification OTP')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-2">
            Vérification du code
        </h2>
        <p class="text-sm text-base-content/60 text-center mb-6">
            Entrez le code à 6 chiffres envoyé à <strong>{{ $email }}</strong>
        </p>

        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error mb-4">
                @foreach($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-control">
                <label class="label"><span class="label-text">Code OTP</span></label>
                <input type="text" name="otp"
                       class="input input-bordered text-center text-2xl tracking-[0.5em] font-bold @error('otp') input-error @enderror"
                       maxlength="6"
                       placeholder="000000"
                       autocomplete="one-time-code"
                       autofocus
                       required>
                @error('otp')
                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-base-content/50 mt-1">
                    Ce code expire dans 10 minutes.
                </p>
            </div>

            <button type="submit" class="btn btn-primary w-full">
                Vérifier le code
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('password.email') }}"
               class="text-sm link link-primary">
                Renvoyer un nouveau code
            </a>
        </div>

        <p class="text-center text-sm mt-2">
            <a href="{{ route('login') }}" class="link link-primary">
                Retour à la connexion
            </a>
        </p>
    </div>
</div>
@endsection
