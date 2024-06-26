<?php

namespace App\Policies;

use App\Models\AddressBook;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressBookPolicy
{
    use HandlesAuthorization;

    public function view(User $user, AddressBook $addressBook)
    {
        return $user->id === $addressBook->user_id
            ? $this->allow()
            : $this->deny('You do not own this address book.');
    }

    public function update(User $user, AddressBook $addressBook)
    {
        return $user->id === $addressBook->user_id
            ? $this->allow()
            : $this->deny('You do not own this address book.');
    }

    public function delete(User $user, AddressBook $addressBook)
    {
        return $user->id === $addressBook->user_id
            ? $this->allow()
            : $this->deny('You do not own this address book.');
    }
}
