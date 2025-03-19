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
Route::get('/history', [index_controller::class, 'get_history_page'])->name('indexHistory');
Route::post('/history', [index_controller::class, 'get_history_page'])->name('indexHistory');
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
    Route::post('/dashboard/upload_slip', [DashboardController::class, 'upload_slip']);

    // Admin routes
    Route::prefix('admin')->middleware(['auth:sanctum', \App\Http\Middleware\AdminCheck::class])->group(function () {
        // Admin dashboard
        Route::get('/', [AdminController::class, 'index'])->name('admin');

        // Room management routes
        Route::get('/rooms', [AdminController::class, 'rooms'])->name('admin.rooms');
        Route::get('/monthly-rooms', [AdminController::class, 'monthlyRooms'])->name('admin.monthly-rooms');
        Route::get('/daily-rooms', [AdminController::class, 'dailyRooms'])->name('admin.daily-rooms');
        Route::post('/update-room-status', [AdminController::class, 'updateRoomStatus'])->name('admin.update-room-status');

        // Payment routes
        Route::get('/pending-payments', [AdminController::class, 'pendingPayments'])->name('admin.pending-payments');
        Route::get('/completed-payments', [AdminController::class, 'completedPayments'])->name('admin.completed-payments');

        // Tenant routes
        Route::get('/monthly-tenants', [AdminController::class, 'monthlyTenants'])->name('admin.monthly-tenants');
        Route::get('/daily-tenants', [AdminController::class, 'dailyTenants'])->name('admin.daily-tenants');

        // Monthly tenant management
        Route::get('/get-available-rooms', [AdminController::class, 'getAvailableRooms'])->name('admin.get-available-rooms');
        Route::post('/create-monthly-tenant', [AdminController::class, 'createMonthlyTenant'])->name('admin.create-monthly-tenant');
        Route::post('/update-monthly-tenant', [AdminController::class, 'updateMonthlyTenant'])->name('admin.update-monthly-tenant');
        Route::delete('/delete-monthly-tenant/{tenantID}', [AdminController::class, 'deleteMonthlyTenant'])->name('admin.delete-monthly-tenant');
        Route::get('/get-tenant-details/{tenantId}', [AdminController::class, 'getTenantDetails'])->name('admin.get-tenant-details');

        // Daily tenant management
        Route::get('/get-available-rooms-daily', [AdminController::class, 'getAvailableRoomsForDaily'])->name('admin.get-available-rooms-daily');
        Route::post('/create-daily-tenant', [AdminController::class, 'createDailyTenant'])->name('admin.create-daily-tenant');
        Route::post('/update-daily-tenant', [AdminController::class, 'updateDailyTenant'])->name('admin.update-daily-tenant');
        Route::delete('/delete-daily-tenant/{tenantID}', [AdminController::class, 'deleteDailyTenant'])->name('admin.delete-daily-tenant');
        Route::get('/get-daily-tenant-details/{tenantId}', [AdminController::class, 'getDailyTenantDetails'])->name('admin.get-daily-tenant-details');
        Route::get('/get-room-tenant/{roomId}', [AdminController::class, 'getRoomTenant'])->name('admin.get-room-tenant');

        // Property management routes
        Route::get('/apartment-info', [AdminController::class, 'apartmentInfo'])->name('admin.apartment-info');
        Route::get('/room-types', [AdminController::class, 'roomTypes'])->name('admin.room-types');
        Route::get('/utilities', [AdminController::class, 'utilities'])->name('admin.utilities');

        // Admin settings routes
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
        Route::post('/update-password', [AdminController::class, 'updatePassword'])->name('admin.update-password');
        Route::post('/update-unit-prices', [AdminController::class, 'updateUnitPrices'])->name('admin.update-unit-prices');
        Route::post('/update-meter-readings', [AdminController::class, 'updateMeterReadings'])->name('admin.update-meter-readings');

        // Bill management routes
        Route::get('/get-room-bills/{roomId}', [AdminController::class, 'getRoomBills'])->name('admin.get-room-bills');
        Route::get('/get-bill/{billId}', [AdminController::class, 'getBill'])->name('admin.get-bill');
        Route::post('/create-bill', [AdminController::class, 'createBill'])->name('admin.create-bill');
        Route::delete('/delete-bill/{billId}', [AdminController::class, 'deleteBill'])->name('admin.delete-bill');
        Route::post('/mark-bill-as-paid/{billId}', [AdminController::class, 'markBillAsPaid'])->name('admin.mark-bill-as-paid');
        Route::get('/print-receipt/{billId}', [AdminController::class, 'printReceipt'])->name('admin.print-receipt');
    });
});
