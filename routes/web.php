<?php

use App\Http\Controllers\Web\RefundRequestController;
use App\Http\Controllers\Web\OrderHistoriesController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Web\PreferencesController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\WhatsAppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MainCategoriesController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\Auth\AuthenticationController;
use App\Http\Controllers\Web\ComplaintsController;
use App\Http\Controllers\Web\ProviderController;
use App\Http\Controllers\Web\PasswordResetController;
use App\Http\Controllers\Web\ShippingController;
use App\Http\Controllers\Web\StylesController;
use App\Http\Controllers\Web\RefundRequestControllerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

@require ('admin_web.php');

Route::get('/', function () {
    return redirect()->route('login');
})->name('/');

Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::view('/', 'admin.dashboard.default')->name('index');
        Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
    });
});


Route::get('auth/{provider}/redirect', [ProviderController::class, 'redirect'])->name('google-auth');
Route::get('auth/{provider}/callback', [ProviderController::class, 'callback']);

Route::get('/authenticate/redirect/{social}', [AuthenticationController::class, 'sociaLiteRedirect'])->name('sociaLite-Redirect');
Route::get('/authenticate/callback/{social}', [AuthenticationController::class, 'sociaLiteCallback'])->name('sociaLite-Callback');

Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticationController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthenticationController::class, 'login']);

    Route::post('register', [WhatsAppController::class, 'register'])->name('register');
    Route::get('verify-email-otp', [WhatsAppController::class, 'showEmailOtpForm'])->name('verify.email.otp.form');
    Route::post('verify-email-otp', [WhatsAppController::class, 'verifyEmailOtp'])->name('verify.email.otp');
    Route::get('resend-email-otp', [WhatsAppController::class, 'resendEmailOtp'])->name('resend.email.otp');
    Route::get('whatsapp-number', [WhatsAppController::class, 'showWhatsAppNumberForm'])->name('whatsapp.number.form');
    Route::post('whatsapp-number', [WhatsAppController::class, 'sendWhatsAppOtp'])->name('whatsapp.number');
    Route::get('verify-whatsapp-otp', [WhatsAppController::class, 'showWhatsAppOtpForm'])->name('verify.whatsapp.otp.form');
    Route::post('verify-whatsapp-otp', [WhatsAppController::class, 'verifyWhatsAppOtp'])->name('verify.whatsapp.otp');
    Route::get('resend-whatsapp-otp', [WhatsAppController::class, 'resendWhatsAppOtp'])->name('resend.whatsapp.otp');

    Route::get('forget-password', [PasswordResetController::class, 'index'])->name('forget.password');
});

Route::middleware('web')->group(function () {
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/', [UsersController::class, 'store'])->name('users.store');
        Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

    });

    Route::group(['prefix' => 'seller'], function () {
        Route::get('/', [ShopController::class, 'index'])->name('seller.index');
        Route::get('/create', [ShopController::class, 'create'])->name('seller.create');
        Route::post('/', [ShopController::class, 'store'])->name('seller.store');
        Route::get('/{id}', [ShopController::class, 'show'])->name('seller.show');
        Route::get('/{id}/edit', [ShopController::class, 'edit'])->name('seller.edit');
        Route::put('/{id}', [ShopController::class, 'update'])->name('seller.update');
        Route::delete('/{id}', [ShopController::class, 'destroy'])->name('seller.destroy');
        Route::get('/{id}', [ShopController::class, 'showProfile'])->middleware('auth');
    });

    //Order
    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
        Route::post('/', [OrdersController::class,'store'])->name('orders.store');
        Route::get('/{id}', [OrdersController::class,'show'])->name('orders.show');
        Route::get('/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
        Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
        Route::delete('/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');
    });
    

    Route::get('shippings', [ShippingController::class, 'index']);
    Route::post('shippings', [ShippingController::class, 'store']);
    Route::get('shippings/{uuid}', [ShippingController::class, 'show']);
    Route::put('shippings/{uuid}', [ShippingController::class, 'update']);
    Route::delete('shippings/{uuid}', [ShippingController::class, 'destroy']);
    Route::post('shippings/calculate', [ShippingController::class, 'calculateShippingCost'
    ]);

    Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');


        Route::group(['prefix'=> 'products'], function () {
            Route::get('/', [ProductsController::class, 'index'])->name('products.index');
            Route::get('/create', [ProductsController::class, 'create'])->name('products.create');
            Route::post('/', [ProductsController::class, 'store'])->name('products.store');
            Route::get('/{id}', [ProductsController::class, 'show'])->name('products.show');
            Route::get('/{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
            Route::put('/{id}', [ProductsController::class, 'update'])->name('products.update');
            Route::delete('/{id}', [ProductsController::class, 'destroy'])->name('products.destroy');
        });
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [MainCategoriesController::class, 'index'])->name('categories.index');
            Route::get('/create', [MainCategoriesController::class, 'create'])->name('categories.create');
            Route::post('/', [MainCategoriesController::class, 'store'])->name('categories.store');
            Route::get('/{id}', [MainCategoriesController::class, 'show'])->name('categories.show');
            Route::get('/{id}/edit', [MainCategoriesController::class, 'edit'])->name('categories.edit');
            Route::put('/{id}', [MainCategoriesController::class, 'update'])->name('categories.update');
            Route::delete('/{id}', [MainCategoriesController::class, 'destroy'])->name('categories.destroy');
        });

        
        Route::group(['prefix' => 'styles'], function () {
            Route::get('/', [StylesController::class, 'index'])->name('styles.index');
            Route::get('/create', [StylesController::class, 'create'])->name('styles.create');
            Route::post('/', [StylesController::class, 'store'])->name('styles.store');
            Route::get('/{id}', [StylesController::class, 'show'])->name('styles.show');
            Route::get('/{id}/edit', [StylesController::class, 'edit'])->name('styles.edit');
            Route::put('/{id}', [StylesController::class, 'update'])->name('styles.update');
            Route::delete('/{id}', [StylesController::class, 'destroy'])->name('styles.destroy');
        });

        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [UsersController::class, 'showProfile'])->name('profile.show');
        });
        Route::group(['prefix' => 'preferences'], function () {
            Route::get('/', [PreferencesController::class, 'index'])->name('preferences.index');
            Route::get('/create', [PreferencesController::class, 'create'])->name('preferences.create');
            Route::post('/', [PreferencesController::class, 'store'])->name('preferences.store');
            Route::get('/{id}', [PreferencesController::class, 'show'])->name('preferences.show');
            Route::get('/{id}/edit', [PreferencesController::class, 'edit'])->name('preferences.edit');
            Route::put('/{id}', [PreferencesController::class, 'update'])->name('preferences.update');
            Route::delete('/{id}', [PreferencesController::class, 'destroy'])->name('preferences.destroy');
        });


        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
            Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
            Route::post('/', [OrdersController::class, 'store'])->name('orders.store');
            Route::get('/{id}', [OrdersController::class, 'show'])->name('orders.show');
            Route::get('/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
            Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
            Route::delete('/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');
        });
        Route::group(['prefix' => 'order.histories'], function () {
            Route::get('/', [OrderHistoriesController::class, 'index'])->name('order.histories.index');
            Route::get('/create', [OrderHistoriesController::class, 'create'])->name('order.histories.create');
            Route::post('/', [OrderHistoriesController::class, 'store'])->name('order.histories.store');
            Route::get('/{id}', [OrderHistoriesController::class, 'show'])->name('order.histories.show');
            Route::get('/{id}/edit', [OrderHistoriesController::class, 'edit'])->name('order.histories.edit');
            Route::put('/{id}', [OrderHistoriesController::class, 'update'])->name('order.histories.update');
            Route::delete('/{id}', [OrderHistoriesController::class, 'destroy'])->name('order.histories.destroy');
        });
        Route::group(['prefix' => 'complaints'], function () {
            Route::get('/', [ComplaintsController::class, 'index'])->name('complaints.index');
            Route::get('/create', [ComplaintsController::class, 'create'])->name('complaints.create');
            Route::post('/', [ComplaintsController::class, 'store'])->name('complaints.store');
            Route::get('/{id}', [ComplaintsController::class, 'show'])->name('complaints.show');
            Route::get('/{id}/edit', [ComplaintsController::class, 'edit'])->name('complaints.edit');
            Route::put('/{id}', [ComplaintsController::class, 'update'])->name('complaints.update');
            Route::delete('/{id}', [ComplaintsController::class, 'destroy'])->name('complaints.destroy');
        });
        Route::group(['prefix' => 'refund-request'], function () {
            Route::get('/', [RefundRequestController::class, 'index'])->name('refund-request.index');
            Route::get('/create', [RefundRequestController::class, 'create'])->name('refund-request.create');
            Route::post('/', [RefundRequestController::class, 'store'])->name('refund-request.store');
            Route::get('/{id}', [RefundRequestController::class, 'show'])->name('refund-request.show');
            Route::get('/{id}/edit', [RefundRequestController::class, 'edit'])->name('refund-request.edit');
            Route::put('/{id}', [RefundRequestController::class, 'update'])->name('refund-request.update');
            Route::delete('/{id}', [RefundRequestController::class, 'destroy'])->name('refund-request.destroy');
        });

    
        Route::group(['prefix' => 'developers'], function () {
            Route::get('/', [UsersController::class, 'developer'])->name('developers.index');
        });
    
});


