<?php

namespace App\Services\Account;

use App\Contracts\Account\AddressServiceInterface;
use App\Http\Requests\Account\StoreAddressRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService implements AddressServiceInterface
{
    private const MAX_ADDRESSES = 5;

    public function getForUser(User $user): Collection
    {
        return Address::where('user_id', $user->id)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();
    }

    public function store(StoreAddressRequest $request, User $user): Address
    {
        //  Limite max 5 adresses
        $count = Address::where('user_id', $user->id)->count();

        if ($count >= self::MAX_ADDRESSES) {
            throw new \DomainException('max_addresses_reached');
        }

        return DB::transaction(function () use ($request, $user) {

            if ($request->boolean('is_default')) {
                Address::where('user_id', $user->id)
                    ->update(['is_default' => false]);
            }

            return Address::create([
                'user_id'     => $user->id,
                'full_name'   => $request->full_name,
                'phone'       => $request->phone,
                'street'      => $request->street,
                'city'        => $request->city,
                'state'       => $request->state,
                'postal_code' => $request->postal_code,
                'country'     => $request->country,
                'is_default'  => $request->boolean('is_default'),
            ]);
        });
    }

    public function delete(Address $address, User $user): void
    {
        //  Ownership check
        if ($address->user_id !== $user->id) {
            abort(403);
        }

        //  Bloquer si commande active liée
        $hasActiveOrder = $address->orders()
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->exists();

        if ($hasActiveOrder) {
            throw new \DomainException('address_has_active_order');
        }

        $address->delete();
    }
}