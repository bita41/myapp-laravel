<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class UsersController extends Controller
{
    public function index(): View
    {
        return view('settings.users.index');
    }
}
