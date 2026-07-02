<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\AuthServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function showForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register($request);

        Auth::login($user);

        return redirect()->route('shop.home')
            ->with('success', 'Bienvenue ! Votre compte a été créé avec succès.');
    }
}
