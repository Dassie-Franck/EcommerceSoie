<?php

// ═══════════════════════════════════════════════════════════════
// App\Http\Controllers\Account\AddressController.php
// ═══════════════════════════════════════════════════════════════
 
namespace App\Http\Controllers\Account;
 
use App\Contracts\Account\AddressServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
 
class AddressController extends Controller
{
    public function __construct(
        private readonly AddressServiceInterface $addressService
    ) {
        $this->middleware('auth');
    }
 
    public function index(): View
    {
        return view('account.addresses', [
            'addresses' => $this->addressService->getForUser(Auth::user()),
        ]);
    }
 
    public function store(StoreAddressRequest $request): RedirectResponse
    {
        try {
            $this->addressService->store($request, Auth::user());
 
            return back()->with('success', 'Adresse ajoutée avec succès.');
 
        } catch (\DomainException $e) {
            return back()->with('error', 'Vous ne pouvez pas avoir plus de 5 adresses.');
        }
    }
 
    public function destroy(Address $address): RedirectResponse
    {
        try {
            $this->addressService->delete($address, Auth::user());
 
            return back()->with('success', 'Adresse supprimée.');
 
        } catch (\DomainException $e) {
            return back()->with('error',
                'Cette adresse est liée à une commande en cours et ne peut pas être supprimée.'
            );
        }
    }
}