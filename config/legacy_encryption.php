<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Legacy encryption key (CI4 compatible)
    |--------------------------------------------------------------------------
    |
    | Same key as in extra/app/Config/Encryption.php. Used for encrypt_id /
    | decrypt_id compatibility (e.g. URLs, IDs) so data encrypted in CI4
    | can be decrypted here and vice versa.
    |
    */
    'key' => env('LEGACY_ENCRYPTION_KEY', ''),

    /*
    | Cipher: must match Encryption config (AES-128-CBC).
    */
    'cipher' => 'AES-128-CBC',

    /*
    |--------------------------------------------------------------------------
    | Password pepper (CI4 app_encrypt_key)
    |--------------------------------------------------------------------------
    |
    | Same as extra/app/Config/Custom.php app_encrypt_key. CI4 hashes passwords
    | as: password_hash( hash_hmac('sha256', $password, pepper), PASSWORD_DEFAULT ).
    | Required for login to work with existing CI4 users.
    |
    */
    'password_pepper' => env('LEGACY_PASSWORD_PEPPER', ''),
];
