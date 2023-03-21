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
    Route::get('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('refresh', [App\Http\Controllers\AuthController::class, 'refresh']);
    Route::get('profile', [App\Http\Controllers\AuthController::class, 'userProfile']);
    Route::delete('destroy/{id}', [App\Http\Controllers\AuthController::class, 'destroy'])->middleware('roles:admin');;
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'products'

], function ($router) {

    Route::get('index', [App\Http\Controllers\ProductController::class, 'index']);
    Route::post('store', [App\Http\Controllers\ProductController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::get('order/{id}', [App\Http\Controllers\ProductController::class, 'order']);
    Route::patch('update/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'orders'

], function ($router) {

    Route::get('index', [App\Http\Controllers\OrderController::class, 'index']);
    Route::post('store', [App\Http\Controllers\OrderController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\OrderController::class, 'show']);
    Route::get('product/{id}', [App\Http\Controllers\OrderController::class, 'product']);
    Route::patch('update/{id}', [App\Http\Controllers\OrderController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\OrderController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'orderlists'

], function ($router) {

    Route::get('index', [App\Http\Controllers\OrderListController::class, 'index']);
    Route::post('store', [App\Http\Controllers\OrderListController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\OrderListController::class, 'show']);
    Route::get('order/{id}', [App\Http\Controllers\OrderListController::class, 'order']);
    Route::patch('update/{id}', [App\Http\Controllers\OrderListController::class, 'update']);
    Route::delete('destroy/{id}', [App\Http\Controllers\OrderListController::class, 'destroy']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'statistics'

], function ($router) {

    Route::get('index', [App\Http\Controllers\StatisticController::class, 'index'])->middleware('roles:admin');
    Route::post('store', [App\Http\Controllers\StatisticController::class, 'store'])->middleware('roles:admin');
    Route::get('show/{id}', [App\Http\Controllers\StatisticController::class, 'show'])->middleware('roles:admin');
    Route::patch('update/{id}', [App\Http\Controllers\StatisticController::class, 'update'])->middleware('roles:admin');
    Route::delete('destroy/{id}', [App\Http\Controllers\StatisticController::class, 'destroy'])->middleware('roles:admin');
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'alerts'

], function ($router) {

    Route::get('index', [App\Http\Controllers\AlertController::class, 'index']);
    Route::post('store', [App\Http\Controllers\AlertController::class, 'store']);
    Route::get('show/{id}', [App\Http\Controllers\AlertController::class, 'show'])->middleware('roles:admin');
    Route::patch('update/{id}', [App\Http\Controllers\AlertController::class, 'update'])->middleware('roles:admin');
    Route::delete('destroy/{id}', [App\Http\Controllers\AlertController::class, 'destroy'])->middleware('roles:admin');
    Route::get('limit/{limit}', [App\Http\Controllers\AlertController::class, 'setLimit'])->middleware('roles:admin');
    Route::get('limit', [App\Http\Controllers\AlertController::class, 'getLimit']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'categories'

], function ($router) {

    Route::get('index', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::post('store', [App\Http\Controllers\CategoryController::class, 'store'])->middleware('roles:admin');
    Route::get('show/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->middleware('roles:admin');
    Route::patch('update/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->middleware('roles:admin');
    Route::delete('destroy/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->middleware('roles:admin');
});
