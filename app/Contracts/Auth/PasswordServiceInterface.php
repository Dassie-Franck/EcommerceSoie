<?php

namespace App\Contracts\Auth;

interface PasswordServiceInterface
{
    public function sendOtp(string $email): void;
    public function verifyOtp(string $email, string $otp): string;
    public function resetPassword(string $email, string $resetToken, string $password): void;
}
