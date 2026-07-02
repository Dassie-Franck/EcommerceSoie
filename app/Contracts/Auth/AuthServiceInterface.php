<?php

namespace App\Contracts\Auth;

use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

interface AuthServiceInterface
{
    public function attempt(LoginRequest $request): ?User;
    public function register(RegisterRequest $request): User;
    public function handleGoogleUser(object $googleUser): User;
    public function logout(): void;
}
