<?php

namespace App\Providers;

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
//        Gate::before(function (User $user, string $ability) {
//            if ($user->hasAccess(Roles::SUPER_ADMIN->value))
//                return true;
//        });
    }
}
