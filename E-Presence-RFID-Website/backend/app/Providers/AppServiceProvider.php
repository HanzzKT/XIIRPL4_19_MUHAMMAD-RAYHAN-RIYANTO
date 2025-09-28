<?php

namespace App\Providers;

use App\Models\Presence;
use App\Observers\PresenceObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('path.public', function () {
            return base_path('../frontend/public');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Presence::observe(PresenceObserver::class);
        View::addLocation(base_path('/../frontend/resources/views'));
    }
}
