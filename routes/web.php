<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\Settings\DictionariesController;
use App\Http\Controllers\Settings\RolesController;
use App\Http\Controllers\Settings\LangJsController;
use App\Http\Controllers\Settings\SettingsController as AppSettingsController;
use App\Http\Controllers\Settings\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit')->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/lang.js', [LangJsController::class, 'langjs'])->name('langjs.lang.js');
        Route::get('/dictionaries', [DictionariesController::class, 'index'])->name('dictionaries.index');
        Route::get('/dictionaries/add', [DictionariesController::class, 'add'])->name('dictionaries.add');
        Route::post('/dictionaries/add_ajax', [DictionariesController::class, 'addAjax'])->name('dictionaries.add_ajax');
        Route::get('/dictionaries/edit/{id}', [DictionariesController::class, 'edit'])->name('dictionaries.edit');
        Route::post('/dictionaries/edit_ajax', [DictionariesController::class, 'editAjax'])->name('dictionaries.edit_ajax');
        Route::get('/dictionaries/dictionaries_server_side', [DictionariesController::class, 'dictionariesServerSide'])
            ->name('dictionaries.dictionaries_server_side')
            ->middleware('throttle:120,1');
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
        Route::get('/settings', [AppSettingsController::class, 'index'])->name('settings.index');
    });
});
