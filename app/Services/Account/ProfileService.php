<?php

namespace App\Services\Account;

use App\Contracts\Account\ProfileServiceInterface;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService implements ProfileServiceInterface
{
    public function update(UpdateProfileRequest $request, User $user): void
    {
        $data = [
            'name'  => $request->name,
            'phone' => $request->phone,
        ];

        if ($request->hasFile('avatar')) {
            // Supprimer l'ancien avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
    }

    public function updatePassword(UpdatePasswordRequest $request, User $user): void
    {
        // current_password déjà vérifié dans UpdatePasswordRequest
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }
}