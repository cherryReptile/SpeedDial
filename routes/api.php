<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\DialController;
use App\Http\Controllers\API\SpeedDialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
//Protected routes
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function(Request $request){
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/add/category', [CategoryController::class, 'add']);
    Route::get('/category/{category}', [CategoryController::class, 'get']);
    Route::get('/categories', [CategoryController::class, 'getAll']);
    Route::patch('/edit/category/{category}', [CategoryController::class, 'edit']);
    Route::delete('/del/category/{category}', [CategoryController::class, 'delete']);
    Route::post('{category}/add/dial', [DialController::class, 'add']);
    Route::get('/dial/{dial}', [DialController::class, 'get']);
    Route::get('/dials', [DialController::class, 'getAll']);
    Route::patch('/edit/dial/{dial}', [DialController::class, 'edit']);
    Route::delete('/del/dial/{dial}', [DialController::class, 'delete']);
    Route::get('/speed/dial/{category}', [SpeedDialController::class, 'get']);
    Route::get('/speed/dials', [SpeedDialController::class, 'getAll']);
});
