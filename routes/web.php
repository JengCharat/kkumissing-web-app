<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\index_controller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/welcome', function () {
    return view('welcome-auth');
})->name('welcome');

Route::get('/', [index_controller::class, 'index'])->name('index');
Route::post('/hire', [index_controller::class, 'hire'])->name('hire');
Route::get('/room-bookings/{roomId}', [index_controller::class, 'getRoomBookings'])->name('room.bookings');

// Authenticated routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
        // Admin dashboard
        Route::get('/', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->index();
        })->name('admin');

        // Room management routes
        Route::get('/rooms', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->rooms();
        })->name('admin.rooms');

        Route::get('/monthly-rooms', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->monthlyRooms();
        })->name('admin.monthly-rooms');

        Route::get('/daily-rooms', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->dailyRooms();
        })->name('admin.daily-rooms');

        // Payment routes
        Route::get('/pending-payments', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->pendingPayments();
        })->name('admin.pending-payments');

        Route::get('/completed-payments', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->completedPayments();
        })->name('admin.completed-payments');

        // Tenant routes
        Route::get('/monthly-tenants', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->monthlyTenants();
        })->name('admin.monthly-tenants');

        Route::get('/daily-tenants', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->dailyTenants();
        })->name('admin.daily-tenants');

        // Property management routes
        Route::get('/apartment-info', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->apartmentInfo();
        })->name('admin.apartment-info');

        Route::get('/room-types', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->roomTypes();
        })->name('admin.room-types');

        Route::get('/utilities', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->utilities();
        })->name('admin.utilities');

        // Admin settings routes
        Route::get('/settings', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->settings();
        })->name('admin.settings');

        Route::post('/update-profile', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateProfile(request());
        })->name('admin.update-profile');

        Route::post('/update-password', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updatePassword(request());
        })->name('admin.update-password');

        // API routes for admin
        Route::post('/update-unit-prices', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateUnitPrices(request());
        })->name('admin.update-unit-prices');

        Route::post('/update-meter-readings', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateMeterReadings(request());
        })->name('admin.update-meter-readings');

        Route::post('/update-room-status', function () {
            if (auth()->user()->usertype !== 'admin') {
                return redirect('/dashboard');
            }
            return app()->make(AdminController::class)->updateRoomStatus(request());
        })->name('admin.update-room-status');
    });
});
