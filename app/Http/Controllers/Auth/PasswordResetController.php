<?php

namespace App\Http\Controllers\Auth;

use App\Contracts\Auth\PasswordServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly PasswordServiceInterface $passwordService
    ) {}

    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(ForgotPasswordRequest $request): RedirectResponse
    {
        $this->passwordService->sendOtp($request->email);

        // ✅ Stocker l'email en session pour l'étape OTP
        session(['otp_email' => $request->email]);

        return redirect()->route('password.otp.form')
            ->with('success', 'Code OTP envoyé à votre adresse email.');
    }

    public function showOtpForm(): View|RedirectResponse
    {
        // 🔐 Bloquer l'accès direct sans email en session
        if (! session('otp_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Veuillez d\'abord entrer votre email.');
        }

        return view('auth.verify-otp', [
            'email' => session('otp_email'),
        ]);
    }

    public function verifyOtp(VerifyOtpRequest $request): RedirectResponse
    {
        try {
            $resetToken = $this->passwordService->verifyOtp(
                $request->email,
                $request->otp
            );

            // ✅ Stocker dans la session proprement
            session([
                'reset_token' => $resetToken,
                'reset_email' => $request->email,
            ]);

            // ✅ Oublier l'email OTP — plus besoin
            session()->forget('otp_email');

            return redirect()->route('password.reset.form');

        } catch (\DomainException $e) {
            $msg = $e->getMessage();

            $error = match(true) {
                $msg === 'otp_not_found'              => 'Aucune demande trouvée.',
                $msg === 'otp_expired'                => 'Le code a expiré. Recommencez.',
                $msg === 'otp_bruteforced'            => 'Trop de tentatives. Recommencez.',
                str_starts_with($msg, 'otp_invalid:') => 'Code incorrect. Il vous reste ' . explode(':', $msg)[1] . ' tentative(s).',
                default                               => 'Code invalide.',
            };

            return back()->withErrors(['otp' => $error]);
        }
    }

    public function showResetForm(): View|RedirectResponse
    {
        // 🔐 Vérifier session reset
        if (! session('reset_token') || ! session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Session expirée. Recommencez.');
        }

        return view('auth.reset-password', [
            'email'       => session('reset_email'),
            'reset_token' => session('reset_token'),
        ]);
    }

    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        try {
            $this->passwordService->resetPassword(
                session('reset_email'),  // ← depuis session
                session('reset_token'),  // ← depuis session
                $request->password
            );

            // ✅ Nettoyer la session
            session()->forget(['reset_token', 'reset_email']);

            return redirect()->route('login')
                ->with('success', 'Mot de passe réinitialisé avec succès.');

        } catch (\DomainException $e) {
            return redirect()->route('password.request')
                ->with('error', 'Session expirée ou token invalide. Recommencez.');
        }
    }
}
