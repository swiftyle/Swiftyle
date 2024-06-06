<?php

use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoriesController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AUth\AuthenticationController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\API\OrdersController;
use App\Http\Controllers\API\WishlistController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('categories', CategoriesController::class);
Route::apiResource('cart', CartController::class);
Route::apiResource('users', UsersController::class);
Route::apiResource('products', ProductsController::class);
Route::apiResource('chats', ChatController::class);
Route::apiResource('orders', OrdersController::class);
Route::apiResource('wishlist', WishlistController::class);

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthenticationController::class, 'logout']);

Route::prefix('api')->group(function () {
    Route::group(['prefix' => 'users'], function () {
        Route::post('/', [UsersController::class, 'store']);
        Route::get('/', [UsersController::class, 'show']);
        Route::put('/{id}', [UsersController::class, 'update']);
        Route::delete('/{id}', [UsersController::class, 'destroy']);
    });

    Route::group(['prefix' => 'products'], function () {
        Route::get('/', [ProductsController::class, 'index']);
        Route::post('/', [ProductsController::class, 'store']);
        Route::get('/{uuid}', [ProductsController::class, 'show']);
        Route::put('/{uuid}', [ProductsController::class, 'update']);
        Route::delete('/{uuid}', [ProductsController::class, 'destroy']);
    });
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoriesController::class, 'index']);
        Route::post('/', [CategoriesController::class, 'store']);
        Route::get('/{uuid}', [CategoriesController::class,'show']);
        Route::put('/{uuid}', [CategoriesController::class, 'update']);
        Route::delete('/{uuid}', [CategoriesController::class, 'destroy']);
    });
    Route::group(['prefix' => 'cart'], function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class,'store']);
        Route::get('/{uuid}', [CartController::class,'show']);
        Route::put('/{uuid}', [CartController::class, 'update']);
        Route::delete('/{uuid}', [CartController::class, 'destroy']);
    });
    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [OrdersController::class, 'index']);
        Route::post('/', [OrdersController::class,'store']);
        Route::get('/{uuid}', [OrdersController::class,'show']);
        Route::put('/{uuid}', [OrdersController::class, 'update']);
        Route::delete('/{uuid}', [OrdersController::class, 'destroy']);
    });
    Route::group(['prefix' => 'wishlist'], function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/', [WishlistController::class,'store']);
        Route::get('/{uuid}', [WishlistController::class,'show']);
        Route::put('/{uuid}', [WishlistController::class, 'update']);
        Route::delete('/{uuid}', [WishlistController::class, 'destroy']);
    });
});