<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Config;

final class LegacyUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials (email). Exclude deleted users.
     *
     * @param  array  $credentials
     * @return (\Illuminate\Contracts\Auth\Authenticatable&\Illuminate\Database\Eloquent\Model)|null
     */
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        $credentials = array_filter(
            $credentials,
            fn ($key) => ! str_contains((string) $key, 'password'),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($credentials)) {
            return null;
        }

        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (is_array($value)) {
                $query->whereIn($key, $value);
            } else {
                $query->where($key, $value);
            }
        }

        $query->where(function ($q): void {
            $q->whereNull('record_status_code')
                ->orWhere('record_status_code', '!=', 'delete');
        });

        return $query->first();
    }

    /**
     * Validate password using legacy pepper + bcrypt (CI4 compatible).
     */
    public function validateCredentials(UserContract $user, #[\SensitiveParameter] array $credentials): bool
    {
        $plain = $credentials['password'] ?? null;
        if ($plain === null) {
            return false;
        }

        $hashed = $user->getAuthPassword();
        if ($hashed === null || $hashed === '') {
            return false;
        }

        $pepper = (string) Config::get('legacy_encryption.password_pepper', '');
        if ($pepper !== '') {
            $peppered = hash_hmac('sha256', $plain, $pepper);
            if (password_verify($peppered, $hashed)) {
                return true;
            }
        }

        return password_verify($plain, $hashed);
    }

    /**
     * Do not rehash on login so existing CI4 hashes remain valid.
     */
    public function rehashPasswordIfRequired(UserContract $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        // No-op: keep legacy hashes unchanged
    }
}
