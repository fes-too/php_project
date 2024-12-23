<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
    }
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate::define('is_admin', function (User $user) {
        //     return $user->is_admin;
        // });
    }
}
