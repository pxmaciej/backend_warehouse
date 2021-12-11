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
    Route::delete('destroy/{id}', [App\Http\Controllers\AuthController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'product'

], function ($router) {

    Route::get('index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('store', [App\Http\Controllers\ProductController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::get('order/{id}', [App\Http\Controllers\ProductController::class, 'order']);
    Route::post('update', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'order'

], function ($router) {

    Route::get('index', [App\Http\Controllers\OrderController::class, 'index']);
    Route::post('store', [App\Http\Controllers\OrderController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\OrderController::class, 'show']);
    Route::get('product/{id}', [App\Http\Controllers\OrderController::class, 'product']);
    Route::post('update', [App\Http\Controllers\OrderController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'orderlist'

], function ($router) {

    Route::get('index', [App\Http\Controllers\OrderListController::class, 'index']);
    Route::post('store', [App\Http\Controllers\OrderListController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\OrderListController::class, 'show']);
    Route::get('order/{id}', [App\Http\Controllers\OrderListController::class, 'order']);
    Route::post('update', [App\Http\Controllers\OrderListController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\OrderListController::class, 'destroy']);
});