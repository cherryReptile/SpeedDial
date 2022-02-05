<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiCategoryController;
use App\Http\Controllers\ApiDialController;
use App\Http\Controllers\ApiSpeedDialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Public routes
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
//Protected routes
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function(Request $request){
        return $request->user();
    });
    Route::post('/logout', [ApiAuthController::class, 'logout']);
    Route::post('/add/category', [ApiCategoryController::class, 'add']);
    Route::get('/category/{category}', [ApiCategoryController::class, 'get']);
    Route::get('/categories', [ApiCategoryController::class, 'getAll']);
    Route::patch('/edit/category/{category}', [ApiCategoryController::class, 'edit']);
    Route::delete('/del/category/{category}', [ApiCategoryController::class, 'delete']);
    Route::post('{category}/add/dial', [ApiDialController::class, 'add']);
    Route::get('/dial/{dial}', [ApiDialController::class, 'get']);
    Route::get('/dials', [ApiDialController::class, 'getAll']);
    Route::patch('/edit/dial/{dial}', [ApiDialController::class, 'edit']);
    Route::delete('/del/dial/{dial}', [ApiDialController::class, 'delete']);
    Route::get('/speed/dial/{dial}', [ApiSpeedDialController::class, 'get']);
    Route::get('/speed/dials', [ApiSpeedDialController::class, 'getAll']);
});
