<?php

namespace App\Services\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Http\Controllers\Shop\CartController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService implements AuthServiceInterface
{
    /**
     * Tentative de connexion
     * Lance une exception métier si compte Google uniquement
     * Retourne null si identifiants incorrects
     */
    public function attempt(LoginRequest $request): ?User
    {
        $user = User::where('email', $request->email)->first();

        // Compte Google uniquement — pas de password
        if ($user && $user->google_id && is_null($user->password)) {
            throw new \DomainException('google_only');
        }

        if (! Auth::attempt($request->credentials(), $request->boolean('remember'))) {
            return null;
        }

        //  Fusion panier anonyme → panier connecté
        CartController::mergeSessionCart(); 

        return Auth::user();
    }

    /**
     * Créer un nouvel utilisateur + wishlist dans une transaction
     */
    public function register(RegisterRequest $request): User
    {
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'client',
            ]);

            Wishlist::create(['user_id' => $user->id]);

            return $user;
        });
    }

    /**
     * Gérer la connexion / création via Google OAuth
     */
    public function handleGoogleUser(object $googleUser): User
    {
        // 1. Déjà inscrit via Google
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            return $user;
        }

        // 2. Email déjà existant → lier le compte Google
        $existing = User::where('email', $googleUser->getEmail())->first();

        if ($existing) {
            $existing->update([
                'google_id' => $googleUser->getId(),
                'avatar'    => $existing->avatar ?? $googleUser->getAvatar(),
                'provider'  => 'google',
            ]);

            return $existing;
        }

        // 3. Nouveau compte Google
        return DB::transaction(function () use ($googleUser) {
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'role'              => 'client',
                'provider'          => 'google',
                'email_verified_at' => now(),
                //  Password aléatoire — jamais null
                'password'          => Hash::make(Str::random(32)),
            ]);

            Wishlist::create(['user_id' => $user->id]);

            return $user;
        });
    }

    /**
     * Déconnexion propre
     */
    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
