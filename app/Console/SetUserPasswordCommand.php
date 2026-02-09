<?php

declare(strict_types=1);

namespace App\Console;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

final class SetUserPasswordCommand extends Command
{
    protected $signature = 'user:set-password {email : User email} {password : New password (min 8 chars)}';

    protected $description = 'Set or reset password for a user by email (hash stored as Laravel bcrypt)';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters.');
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email [{$email}] not found.");
            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password updated for [{$email}]. You can now log in with this password.");
        return self::SUCCESS;
    }
}
