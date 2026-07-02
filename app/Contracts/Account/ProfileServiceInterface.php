<?php

namespace App\Contracts\Account;

use App\Models\User;
use App\Http\Requests\Account\UpdateProfileRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;

interface ProfileServiceInterface
{
    public function update(UpdateProfileRequest $request, User $user): void;
    public function updatePassword(UpdatePasswordRequest $request, User $user): void;
}