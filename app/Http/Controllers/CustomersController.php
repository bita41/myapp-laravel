<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final class CustomersController extends Controller
{
    public function index(): View
    {
        return view('customers.index');
    }
}
