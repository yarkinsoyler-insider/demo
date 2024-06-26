<?php

use App\Models\AddressBook;
use App\Policies\AddressBookPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        AddressBook::class => AddressBookPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
