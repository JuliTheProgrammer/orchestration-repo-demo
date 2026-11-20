<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Registered::class, fn (Registered $event) => activity('auth')
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->log('registered'));

        Event::listen(Login::class, fn (Login $event) => activity('auth')
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->log('logged in'));

        Event::listen(Logout::class, fn (Logout $event) => $event->user && activity('auth')
            ->causedBy($event->user)
            ->performedOn($event->user)
            ->log('logged out'));

        Event::listen(Failed::class, fn (Failed $event) => activity('auth')
            ->withProperties(['email' => $event->credentials['email'] ?? null])
            ->log('failed login attempt'));
    }
}
