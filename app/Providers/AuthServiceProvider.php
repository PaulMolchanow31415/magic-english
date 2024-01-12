<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // https://runebook.dev/ru/docs/laravel/docs/10.x/authorization#gates:~:text=Policy%20Auto%2DDiscovery
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void {
        //
    }
}
