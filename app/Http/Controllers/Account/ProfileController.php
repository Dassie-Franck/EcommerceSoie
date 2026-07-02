<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Account\ProfileController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Account;
 
use App\Contracts\Account\ProfileServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
 
class ProfileController extends Controller
{
    public function __construct(
        private readonly ProfileServiceInterface $profileService
    ) {
        $this->middleware('auth');
    }
 
    public function show(): View
    {
        return view('account.profile', ['user' => Auth::user()]);
    }
 
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->profileService->update($request, Auth::user());
 
        return back()->with('success', 'Profil mis à jour avec succès.');
    }
 
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $this->profileService->updatePassword($request, Auth::user());
 
        return back()->with('success', 'Mot de passe modifié avec succès.');
    }
}