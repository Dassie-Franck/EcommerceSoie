<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordOtpToken extends Model
{
    protected $fillable = [
        'email', 'otp_hash', 'attempts', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'attempts'   => 'integer',
    ];

    // Vérifier si le token est expiré
    public function isExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    // Vérifier si trop de tentatives (max 3)
    public function isBruteForced(): bool
    {
        return $this->attempts >= 3;
    }

    // Vérifier le code OTP saisi
    public function verify(string $otp): bool
    {
        return \Illuminate\Support\Facades\Hash::check($otp, $this->otp_hash);
    }
}
