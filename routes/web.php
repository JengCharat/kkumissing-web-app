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
Route::post('/hire', [index_controller::class, 'hire']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes - protected by auth and admin check
    Route::middleware(['auth:sanctum'])->group(function () {
        // Admin middleware to check if user is admin
        Route::middleware(['web'])->group(function () {
            // Admin dashboard
            Route::get('/admin', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->index();
            })->name('admin');

            // Room management
            Route::get('/admin/rooms', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->rooms();
            })->name('admin.rooms');

            // Monthly rooms
            Route::get('/admin/monthly-rooms', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->monthlyRooms();
            })->name('admin.monthly-rooms');

            // Daily rooms
            Route::get('/admin/daily-rooms', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->dailyRooms();
            })->name('admin.daily-rooms');

            // Pending payments
            Route::get('/admin/pending-payments', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->pendingPayments();
            })->name('admin.pending-payments');

            // Completed payments
            Route::get('/admin/completed-payments', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->completedPayments();
            })->name('admin.completed-payments');

            // Monthly tenants
            Route::get('/admin/monthly-tenants', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->monthlyTenants();
            })->name('admin.monthly-tenants');

            // Daily tenants
            Route::get('/admin/daily-tenants', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->dailyTenants();
            })->name('admin.daily-tenants');

            // Apartment info
            Route::get('/admin/apartment-info', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->apartmentInfo();
            })->name('admin.apartment-info');

            // Room types
            Route::get('/admin/room-types', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->roomTypes();
            })->name('admin.room-types');

            // Utilities
            Route::get('/admin/utilities', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->utilities();
            })->name('admin.utilities');

            // Admin settings
            Route::get('/admin/settings', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->settings();
            })->name('admin.settings');

            // Admin profile update
            Route::post('/admin/update-profile', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->updateProfile(request());
            })->name('admin.update-profile');

            // Admin password update
            Route::post('/admin/update-password', function () {
                if (auth()->user()->usertype !== 'admin') {
                    return redirect('/dashboard');
                }
                return app()->make(AdminController::class)->updatePassword(request());
            })->name('admin.update-password');

            // API routes for admin
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
});
