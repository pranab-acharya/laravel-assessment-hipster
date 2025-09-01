<?php

namespace App\Providers;

use App\Listeners\UpdateUserLoginStatus;
use App\Listeners\UpdateUserLogoutStatus;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            UpdateUserLoginStatus::class,
        ],
        Logout::class => [
            UpdateUserLogoutStatus::class,
        ],
    ];
}
