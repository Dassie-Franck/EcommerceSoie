<?php

namespace App\Services\Auth;

use App\Contracts\Auth\PasswordServiceInterface;
use App\Mail\OtpPasswordReset;
use App\Models\PasswordOtpToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordService implements PasswordServiceInterface
{
    private const OTP_EXPIRY_MINUTES   = 10;
    private const RESET_EXPIRY_MINUTES = 5;
    private const MAX_ATTEMPTS         = 3;

    /**
     * Générer et envoyer un OTP par email
     */
    public function sendOtp(string $email): void
    {
        $user = User::where('email', $email)->firstOrFail();

        // Supprimer tout ancien OTP
        PasswordOtpToken::where('email', $email)->delete();

        // OTP 6 chiffres cryptographiquement sûr
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordOtpToken::create([
            'email'      => $email,
            'otp_hash'   => Hash::make($otp),
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);

        Mail::to($email)->send(new OtpPasswordReset($otp, $user->name));
    }

    /**
     * Vérifier l'OTP — retourne le reset_token signé
     * Lance des exceptions métier typées
     */
    public function verifyOtp(string $email, string $otp): string
    {
        $record = PasswordOtpToken::where('email', $email)->first();

        if (! $record) {
            throw new \DomainException('otp_not_found');
        }

        if ($record->isExpired()) {
            $record->delete();
            throw new \DomainException('otp_expired');
        }

        if ($record->isBruteForced()) {
            $record->delete();
            throw new \DomainException('otp_bruteforced');
        }

        if (! $record->verify($otp)) {
            $record->increment('attempts');
            $remaining = self::MAX_ATTEMPTS - $record->fresh()->attempts;
            throw new \DomainException("otp_invalid:{$remaining}");
        }

        //  OTP valide → reset_token signé 64 chars
        $resetToken = Str::random(64);

        $record->update([
            'otp_hash'   => Hash::make($resetToken),
            'expires_at' => now()->addMinutes(self::RESET_EXPIRY_MINUTES),
            'attempts'   => 0,
        ]);

        return $resetToken;
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(string $email, string $resetToken, string $password): void
    {
        $record = PasswordOtpToken::where('email', $email)->first();

        if (! $record || $record->isExpired()) {
            throw new \DomainException('reset_expired');
        }

        if (! Hash::check($resetToken, $record->otp_hash)) {
            throw new \DomainException('reset_invalid');
        }

        User::where('email', $email)
            ->update(['password' => Hash::make($password)]);

        $record->delete();
    }
}
