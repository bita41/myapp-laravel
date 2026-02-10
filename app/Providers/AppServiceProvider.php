<?php

namespace App\Providers;

use App\Auth\LegacyUserProvider;
use App\Console\SetUserPasswordCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
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
        Auth::provider('legacy_eloquent', function ($app, array $config) {
            return new LegacyUserProvider($app['hash'], $config['model']);
        });

        $this->commands([SetUserPasswordCommand::class]);

        // Vite assets: ?v= for cache-busting (same logic as version() / asset_v)
        Vite::createAssetPathsUsing(function (string $path, ?bool $secure = null): string {
            return asset($path, $secure) . '?v=' . urlencode(version());
        });
    }
}
