<?php

use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;
use App\Http\Controllers\index_controller;

Route::get('/', function () {
    return view('welcome-auth');
});

Route::get('/index', [index_controller::class, 'index']);
Route::post('/hire', [index_controller::class, 'hire']);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/admin', function () {
        if (auth()->user()->isAdmin()) {
            return view('admin');
        } else {
            return redirect('/dashboard');
        }
    })->name('admin');
});
