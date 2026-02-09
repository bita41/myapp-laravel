<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

final class AuthController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $email = $request->input('user.email');
        $password = $request->input('user.password');

        $user = User::where('email', $email)
            ->where(function ($q): void {
                $q->whereNull('record_status_code')->orWhere('record_status_code', '!=', 'delete');
            })
            ->first();

        if (config('app.debug')) {
            Log::info('Login attempt', [
                'email' => $email,
                'user_found' => $user !== null,
            ]);
        }

        $ok = Auth::attempt([
            'email' => $email,
            'password' => $password,
        ], false);

        if (! $ok) {
            $debugHint = '';
            if (config('app.debug')) {
                $debugHint = $user === null
                    ? ' (Debug: no user with this email)'
                    : ' (Debug: wrong password or hash mismatch)';
                Log::warning('Login failed', [
                    'email' => $email,
                    'reason' => $user === null ? 'user_not_found' : 'password_mismatch',
                ]);
            }
            throw ValidationException::withMessages([
                'user.email' => __('Invalid email or password.') . $debugHint,
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
