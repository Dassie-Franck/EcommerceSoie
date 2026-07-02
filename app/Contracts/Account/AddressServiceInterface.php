<?php

namespace App\Contracts\Account;

use App\Models\Address;
use App\Models\User;
use App\Http\Requests\Account\StoreAddressRequest;
use Illuminate\Database\Eloquent\Collection;

interface AddressServiceInterface
{
    public function getForUser(User $user): Collection;
    public function store(StoreAddressRequest $request, User $user): Address;
    public function delete(Address $address, User $user): void;
}