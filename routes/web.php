<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\Settings\DictionariesController;
use App\Http\Controllers\Settings\LangJsController;
use App\Http\Controllers\Settings\RolesController;
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
    Route::get('/admin/lang.js', LangJsController::class)->name('admin.lang.js');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects.index');
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');

    Route::prefix('settings')->name('settings.')->group(function () {
    
        // Dictionaries
        Route::prefix('dictionaries')
            ->name('dictionaries.')
            ->controller(DictionariesController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/add', 'add')->name('add');
                Route::post('/add_ajax', 'addAjax')->name('add_ajax');
                Route::get('/edit/{id}', 'edit')->name('edit');
                Route::post('/edit_ajax', 'editAjax')->name('edit_ajax');
                Route::get('/dictionaries_server_side', 'dictionariesServerSide')
                    ->name('dictionaries_server_side')
                    ->middleware('throttle:120,1');
            });
        
        // Users
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        
        // Roles
        Route::get('/roles', [RolesController::class, 'index'])->name('roles.index');
        
        // App Settings
        Route::get('/', [AppSettingsController::class, 'index'])->name('index');
    });
});
