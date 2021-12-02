<?php

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
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::get('users', [App\Http\Controllers\AuthController::class, 'allUserProfile'])->middleware('roles:admin');
    Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('checkToken', [App\Http\Controllers\AuthController::class, 'checkToken']);
    Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
    Route::patch('update', [App\Http\Controllers\AuthController::class, 'update']);
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::post('profile', [App\Http\Controllers\AuthController::class, 'userProfile']);
    Route::delete('destroy/{user_id}', [App\Http\Controllers\AuthController::class, 'destroy']);
});

