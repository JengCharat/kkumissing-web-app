<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;
use App\Http\Controllers\index_controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
Route::get('/welcome', function () {
    return view('welcome-auth');
});

Route::get('/', [index_controller::class, 'index']);
// Route::post('/hire', [index_controller::class, 'hire']);

// Route::post('/dashboard', [DashboardController::class, 'upload_slip']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'upload_slip']);

    // Admin routes - protected by auth and admin check
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/admin', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->index();
        })->name('admin');

        Route::post('/admin/update-unit-prices', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateUnitPrices(request());
        })->name('admin.update-unit-prices');

        Route::post('/admin/update-meter-readings', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateMeterReadings(request());
        })->name('admin.update-meter-readings');

        Route::post('/admin/update-room-status', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateRoomStatus(request());
        })->name('admin.update-room-status');
    });
});
