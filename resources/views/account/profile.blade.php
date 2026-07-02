@extends('layouts.account')
@section('title', 'Mon Profil')

@section('account-content')

{{-- ── MON PROFIL ────────────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:20px;">

    {{-- Header --}}
    <div>
        <h1 style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:#1a1a0e;">
            Mon Profil
        </h1>
        <p style="font-size:0.875rem;color:#888;margin-top:4px;">
            Gérez vos informations personnelles
        </p>
    </div>

    {{-- ── INFOS PERSONNELLES ─────────────────────────────────── --}}
    <div class="account-card">
        <div class="account-card-header">
            <span class="account-card-title">Informations personnelles</span>
        </div>
        <div style="padding:24px;">

            {{-- Avatar + nom --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid #f0f0ec;">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}"
                         alt="{{ $user->name }}"
                         style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid var(--gold-pale);">
                @else
                    <div class="avatar-circle">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p style="font-weight:700;font-size:1rem;color:#1a1a0e;">{{ $user->name }}</p>
                    <p style="font-size:0.8rem;color:#888;margin-top:2px;">{{ $user->email }}</p>
                    @if($user->google_id)
                        <span style="display:inline-flex;align-items:center;gap:4px;background:#f0f0f0;padding:2px 8px;border-radius:999px;font-size:0.68rem;color:#666;margin-top:4px;">
                            <svg style="width:12px;height:12px;" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Compte Google
                        </span>
                    @endif
                </div>
            </div>

            {{-- Formulaire modification profil --}}
            <form method="POST" action="{{ route('account.profile.update') }}"
                  enctype="multipart/form-data">
                @csrf @method('PATCH')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div>
                        <label class="account-label">Nom complet</label>
                        <input type="text" name="name" class="account-input"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p style="color:#dc2626;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="account-label">Téléphone</label>
                        <input type="text" name="phone" class="account-input"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+33 6 00 00 00 00">
                        @error('phone')
                            <p style="color:#dc2626;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="grid-column:1/-1;">
                        <label class="account-label">Email</label>
                        <input type="email" class="account-input"
                               value="{{ $user->email }}"
                               disabled
                               style="background:#f8f8f6;color:#888;cursor:not-allowed;">
                        <p style="font-size:0.7rem;color:#aaa;margin-top:4px;">
                            L'email ne peut pas être modifié.
                        </p>
                    </div>

                    <div style="grid-column:1/-1;">
                        <label class="account-label">Photo de profil</label>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                                   style="flex:1;padding:8px;border:1.5px solid var(--border);border-radius:8px;font-size:0.8rem;">
                        </div>
                        <p style="font-size:0.7rem;color:#aaa;margin-top:4px;">
                            JPEG, PNG, WebP — Max 2MB
                        </p>
                        @error('avatar')
                            <p style="color:#dc2626;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f0f0ec;">
                    <button type="submit" class="btn-gold-account">
                        <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── CHANGER MOT DE PASSE ───────────────────────────────── --}}
    @if(!$user->google_id)
    <div class="account-card">
        <div class="account-card-header">
            <span class="account-card-title">Changer le mot de passe</span>
        </div>
        <div style="padding:24px;">
            <form method="POST" action="{{ route('account.profile.password') }}">
                @csrf @method('PATCH')

                <div style="display:flex;flex-direction:column;gap:16px;max-width:400px;">

                    <div>
                        <label class="account-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="account-input"
                               placeholder="••••••••" required>
                        @error('current_password')
                            <p style="color:#dc2626;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="account-label">Nouveau mot de passe</label>
                        <input type="password" name="password" class="account-input"
                               placeholder="••••••••" required>
                        <p style="font-size:0.7rem;color:#aaa;margin-top:4px;">
                            Min. 8 caractères, majuscule, minuscule et chiffre.
                        </p>
                        @error('password')
                            <p style="color:#dc2626;font-size:0.72rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="account-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="account-input"
                               placeholder="••••••••" required>
                    </div>

                    <div>
                        <button type="submit" class="btn-gold-account">
                            <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Mettre à jour le mot de passe
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
    @endif

</div>

@endsection
