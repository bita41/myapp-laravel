<?php

declare(strict_types=1);

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

if (!function_exists('version')) {
    /**
     * Cache-busting version (CI4-style): dev => time(), production => APP_VERSION from .env.
     */
    function version(): string
    {
        if (App::environment('local')) {
            return (string) time();
        }
        return (string) Config::get('custom.app_version', '1');
    }
}

if (!function_exists('asset_v')) {
    /**
     * Asset URL with ?v= for cache-busting (uses version()).
     */
    function asset_v(string $path): string
    {
        return asset($path) . '?v=' . urlencode(version());
    }
}

if (!function_exists('is_valid_email')) {
    function is_valid_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('clean_string')) {
    function clean_string(string $str): string
    {
        return trim($str);
    }
}
