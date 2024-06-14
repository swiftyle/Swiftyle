<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AppCouponController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\MainCategoryController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderHistoryController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\RefundController;
use App\Http\Controllers\Api\RefundRequestController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ShippingController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\ShopCouponController;
use App\Http\Controllers\Api\StyleController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\WishlistItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('register', [AuthenticationController::class, 'register']);

Route::prefix('auth/google')->group(function () {
    Route::get('', [ProviderController::class, 'redirect']);
    Route::get('callback', [ProviderController::class, 'callbackGoogle']);
});

// Protected routes with 'jwt.auth' middleware
Route::middleware(['jwt.auth'])->group(function () {

    // Products
    Route::prefix('products')->group(function () {
        Route::post('', [ProductController::class, 'create']);
        Route::get('', [ProductController::class, 'read']);
        Route::get('{id}', [ProductController::class, 'readById']);
        Route::put('{id}', [ProductController::class, 'update']);
        Route::delete('{id}', [ProductController::class, 'delete']);
    });

    // Main Categories
    Route::prefix('main-categories')->group(function () {
        Route::post('', [MainCategoryController::class, 'create']);
        Route::get('', [MainCategoryController::class, 'read']);
        Route::get('{id}', [MainCategoryController::class, 'readById']);
        Route::put('{id}', [MainCategoryController::class, 'update']);
        Route::delete('{id}', [MainCategoryController::class, 'delete']);
    });

    // Sub Categories
    Route::prefix('sub-categories')->group(function () {
        Route::post('', [SubCategoryController::class, 'create']);
        Route::get('', [SubCategoryController::class, 'read']);
        Route::get('{id}', [SubCategoryController::class, 'readById']);
        Route::put('{id}', [SubCategoryController::class, 'update']);
        Route::delete('{id}', [SubCategoryController::class, 'delete']);
    });

    //Color
    Route::prefix('colors')->group(function () {
        Route::post('', [ColorController::class, 'create']);
        Route::get('', [ColorController::class, 'read']);
        Route::get('{id}', [ColorController::class, 'readById']);
        Route::put('{id}', [ColorController::class, 'update']);
        Route::delete('{id}', [ColorController::class, 'delete']);
    });

    //Style
    Route::prefix('styles')->group(function () {
        Route::post('', [StyleController::class, 'create']);
        Route::get('', [StyleController::class, 'read']);
        Route::get('{id}', [StyleController::class, 'readById']);
        Route::put('{id}', [StyleController::class, 'update']);
        Route::delete('{id}', [StyleController::class, 'delete']);
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::post('', [UserController::class, 'create']);
        Route::get('', [UserController::class, 'read']);
        Route::get('{id}', [UserController::class, 'readById']);
        Route::put('{id}', [UserController::class, 'update']);
        Route::delete('{id}', [UserController::class, 'delete']);
        Route::get('{id}/restore', [UserController::class, 'restore']);
    });

    //Chats
    Route::prefix('chats')->group(function () {
        Route::post('', [ChatController::class, 'create']);
        Route::get('', [ChatController::class, 'read']);
        Route::get('{id}', [ChatController::class, 'readById']);
        Route::put('{id}', [ChatController::class, 'update']);
        Route::delete('{id}', [ChatController::class, 'delete']);
    });

    //Messages
    Route::prefix('messages')->group(function () {
        Route::post('', [MessageController::class, 'create']);
        Route::get('', [MessageController::class, 'read']);
        Route::get('{id}', [MessageController::class, 'readById']);
        Route::put('{id}', [MessageController::class, 'update']);
        Route::delete('{id}', [MessageController::class, 'delete']);
    });

    // Addresses
    Route::prefix('addresses')->group(function () {
        Route::post('', [AddressController::class, 'create']);
        Route::get('', [AddressController::class, 'read']);
        Route::get('{id}', [AddressController::class, 'readById']);
        Route::put('{id}', [AddressController::class, 'update']);
        Route::delete('{id}', [AddressController::class, 'delete']);
    });

    // Promotions
    Route::prefix('promotions')->group(function () {
        Route::post('', [PromotionController::class, 'create']);
        Route::get('', [PromotionController::class, 'read']);
        Route::get('{id}', [PromotionController::class, 'readById']);
        Route::put('{id}', [PromotionController::class, 'update']);
        Route::delete('{id}', [PromotionController::class, 'delete']);
    });

    //ShopCoupons
    Route::prefix('shop-coupons')->group(function () {
        Route::post('', [ShopCouponController::class, 'create']);
        Route::get('', [ShopCouponController::class, 'read']);
        Route::get('{id}', [ShopCouponController::class, 'readById']);
        Route::put('{id}', [ShopCouponController::class, 'update']);
        Route::delete('{id}', [ShopCouponController::class, 'delete']);
    });

    //Shop 
    Route::prefix('shops')->group(function () {
        Route::post('', [ShopController::class, 'create']);
        Route::get('', [ShopController::class, 'read']);
        Route::get('{id}', [ShopController::class, 'readById']);
        Route::put('{id}', [ShopController::class, 'update']);
        Route::delete('{id}', [ShopController::class, 'delete']);
    });

    //AppCoupons
    Route::prefix('app-coupons')->group(function () {
        Route::post('', [AppCouponController::class, 'create']);
        Route::get('', [AppCouponController::class, 'read']);
        Route::get('{id}', [AppCouponController::class, 'readById']);
        Route::put('{id}', [AppCouponController::class, 'update']);
        Route::delete('{id}', [AppCouponController::class, 'delete']);
    });

    //Orders
    Route::prefix('orders')->group(function () {
        Route::post('', [OrderController::class, 'create']);
        Route::get('', [OrderController::class, 'read']);
        Route::get('{id}', [OrderController::class, 'readById']);
        Route::put('{id}', [OrderController::class, 'updateStatus']);
        Route::delete('{id}', [OrderController::class, 'delete']);
    });

    //Carts
    Route::prefix('carts')->group(function () {
        Route::post('', [CartController::class, 'create']);
        Route::get('', [CartController::class, 'read']);
        Route::get('{id}', [CartController::class, 'readById']);
        Route::put('{id}', [CartController::class, 'update']);
        Route::delete('{id}', [CartController::class, 'delete']);
    });

    //CartItem
    Route::prefix('cart-item')->group(function () {
        Route::post('', [CartItemController::class, 'create']);
        Route::get('', [CartItemController::class, 'read']);
        Route::get('{id}', [CartItemController::class, 'readById']);
        Route::put('{id}', [CartItemController::class, 'update']);
        Route::delete('{id}', [CartItemController::class, 'delete']);
    });

    //Wishlist
    Route::prefix('wishlists')->group(function () {
        Route::post('', [WishlistController::class, 'create']);
        Route::get('', [WishlistController::class, 'read']);
        Route::get('{id}', [WishlistController::class, 'readById']);
        Route::put('{id}', [WishlistController::class, 'update']);
        Route::delete('{id}', [WishlistController::class, 'delete']);
    });

    //WishlistItem
    Route::prefix('wishlist-item')->group(function () {
        Route::post('', [WishlistItemController::class, 'create']);
        Route::get('', [WishlistItemController::class, 'read']);
        Route::get('{id}', [WishlistItemController::class, 'readById']);
        Route::put('{id}', [WishlistItemController::class, 'update']);
        Route::delete('{id}', [WishlistItemController::class, 'delete']);
    });

    //Reviews
    Route::prefix('reviews')->group(function () {
        Route::post('', [ReviewController::class, 'create']);
        Route::get('', [ReviewController::class, 'read']);
        Route::get('{id}', [ReviewController::class, 'readById']);
        Route::put('{id}', [ReviewController::class, 'update']);
        Route::delete('{id}', [ReviewController::class, 'delete']);
    });

    //Checkouts
    Route::prefix('checkouts')->group(function () {
        Route::post('', [CheckoutController::class, 'create']);
        Route::get('', [CheckoutController::class, 'read']);
        Route::get('{id}', [CheckoutController::class, 'readById']);
        Route::put('{id}', [CheckoutController::class, 'update']);
        Route::delete('{id}', [CheckoutController::class, 'delete']);
    });

    //Shipping
    Route::prefix('shippings')->group(function () {
        Route::post('', [ShippingController::class, 'create']);
        Route::get('', [ShippingController::class, 'read']);
        Route::get('{id}', [ShippingController::class, 'readById']);
        Route::put('{id}', [ShippingController::class, 'update']);
        Route::delete('{id}', [ShippingController::class, 'delete']);
    });

    //Payments
    Route::prefix('payments')->group(function () {
        Route::post('', [PaymentController::class, 'create']);
        Route::get('', [PaymentController::class, 'read']);
        Route::get('{id}', [PaymentController::class, 'readById']);
        Route::put('{id}', [PaymentController::class, 'update']);
        Route::delete('{id}', [PaymentController::class, 'delete']);
    });

    //Transactions
    Route::prefix('transactions')->group(function () {
        Route::post('', [TransactionController::class, 'create']);
        Route::get('', [TransactionController::class, 'read']);
        Route::get('{id}', [TransactionController::class, 'readById']);
        Route::put('{id}', [TransactionController::class, 'update']);
        Route::delete('{id}', [TransactionController::class, 'delete']);
    });

    //Complaints
    Route::prefix('complaints')->group(function () {
        Route::post('', [ComplaintController::class, 'create']);
        Route::get('', [ComplaintController::class, 'read']);
        Route::get('{id}', [ComplaintController::class, 'readById']);
        Route::put('{id}', [ComplaintController::class, 'update']);
        Route::delete('{id}', [ComplaintController::class, 'delete']);
    });

    //OrderHistories
    Route::prefix('order-histories')->group(function () {
        Route::post('', [OrderHistoryController::class, 'create']);
        Route::get('', [OrderHistoryController::class, 'read']);
        Route::get('{id}', [OrderHistoryController::class, 'readById']);
        Route::put('{id}', [OrderHistoryController::class, 'update']);
        Route::delete('{id}', [OrderHistoryController::class, 'delete']);
    });

    //RefundRequest
    Route::prefix('refund-requests')->group(function () {
        Route::post('', [RefundRequestController::class, 'create']);
        Route::get('', [RefundRequestController::class, 'read']);
        Route::get('{id}', [RefundRequestController::class, 'readById']);
        Route::put('{id}', [RefundRequestController::class, 'update']);
        Route::delete('{id}', [RefundRequestController::class, 'delete']);
    });

    //Refund
    Route::prefix('refunds')->group(function () {
        Route::post('', [RefundController::class, 'create']);
        Route::get('', [RefundController::class, 'read']);
        Route::get('{id}', [RefundController::class, 'readById']);
        Route::put('{id}', [RefundController::class, 'update']);
        Route::delete('{id}', [RefundController::class, 'delete']);
    });

    // Logout
    Route::post('logout', [AuthenticationController::class, 'logout']);

});

