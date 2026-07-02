<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Connexion Google échouée. Veuillez réessayer.');
        }

        $user = $this->authService->handleGoogleUser($googleUser);

        Auth::login($user, true);

        $message = 'Bienvenue ' . $user->name . ' !';

        return redirect()->intended(
            $user->role === 'admin'
                ? route('admin.dashboard')
                : route('shop.home')
        )->with('success', $message);
    }
}
