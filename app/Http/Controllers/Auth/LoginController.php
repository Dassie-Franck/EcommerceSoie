<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $user = $this->authService->attempt($request);

            if (! $user) {
                return back()->withErrors([
                    'email' => 'Identifiants incorrects.',
                ])->onlyInput('email');
            }

            //  Régénérer la session — protection fixation
            $request->session()->regenerate();

            return redirect()->intended(
                $user->role === 'admin'
                    ? route('admin.dashboard')
                    : route('shop.home')
            );

        } catch (\DomainException $e) {
            if ($e->getMessage() === 'google_only') {
                return back()->withErrors([
                    'email' => 'Ce compte utilise la connexion Google.',
                ])->onlyInput('email');
            }

            return back()->with('error', 'Une erreur est survenue.');
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();

        return redirect()->route('login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
