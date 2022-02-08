<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpeedDialController;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckNonAuth;
use Illuminate\Support\Facades\Route;

//public routes
Route::middleware(CheckAuth::class)->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('auth');
    Route::post('/', [AuthController::class, 'auth'])->name('auth.post');
});
//private routes
Route::middleware(CheckNonAuth::class)->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::post('/', [SpeedDialController::class, 'categoryCreate'])->name('category.create');
        Route::post('{category}', [SpeedDialController::class, 'categoryDelete'])->name('category.delete');
        Route::post('{category}/dial', [SpeedDialController::class, 'dialCreate'])->name('dial.create');
        Route::post('{dial}/activity', [SpeedDialController::class, 'activity'])->name('dial.activity');
        Route::post('{dial}/delete', [SpeedDialController::class, 'dialDelete'])->name('dial.delete');
    });
});
