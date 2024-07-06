<?php

use App\Http\Controllers\Web\RefundRequestController;
use App\Http\Controllers\Web\OrderHistoriesController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Web\PreferencesController;
use App\Http\Controllers\Web\ShopController;
use App\Http\Controllers\Web\WhatsAppController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\MainCategoriesController;
use App\Http\Controllers\Web\SubCategoriesController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\Auth\AuthenticationController;
use App\Http\Controllers\Web\ComplaintsController;
use App\Http\Controllers\Web\ProviderController;
use App\Http\Controllers\Web\PasswordResetController;
use App\Http\Controllers\Web\ShippingController;
use App\Http\Controllers\Web\StylesController;

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

    Route::get('register', [AuthenticationController::class, 'showRegistrationForm'])->name('show.register');
    Route::post('register', [AuthenticationController::class, 'register'])->name('register');
    Route::get('verify-email-otp', [WhatsAppController::class, 'showEmailOtpForm'])->name('verify.email.otp.form');
    Route::post('verify-otp', [AuthenticationController::class, 'verifyOtp'])->name('verify-otp');
    Route::get('resend-email-otp', [WhatsAppController::class, 'resendEmailOtp'])->name('resend.email.otp');
    // Route::get('whatsapp-number', [WhatsAppController::class, 'showWhatsAppNumberForm'])->name('whatsapp.number.form');
    // Route::post('whatsapp-number', [WhatsAppController::class, 'sendWhatsAppOtp'])->name('whatsapp.number');
    // Route::get('verify-whatsapp-otp', [WhatsAppController::class, 'showWhatsAppOtpForm'])->name('verify.whatsapp.otp.form');
    // Route::post('verify-whatsapp-otp', [WhatsAppController::class, 'verifyWhatsAppOtp'])->name('verify.whatsapp.otp');
    // Route::get('resend-whatsapp-otp', [WhatsAppController::class, 'resendWhatsAppOtp'])->name('resend.whatsapp.otp');

    Route::get('/forgot-password', [AuthenticationController::class, 'showForgetPasswordForm'])->name('forgot-password');
    // Route for handling forgot password form submission
    Route::post('/forgot-password', [AuthenticationController::class, 'forgetPassword'])->name('forgot-password.submit');
    // Route for showing reset password form
    Route::get('/reset-password', [AuthenticationController::class, 'showResetPasswordForm'])->name('reset-password');
    // Route for handling reset password form submission
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('reset-password.submit');
});

Route::middleware('web')->group(function () {

    //Users
    Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
        Route::get('/', [UsersController::class, 'index'])->name('users.index');
        Route::get('/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('/', [UsersController::class, 'store'])->name('users.store');
        Route::get('/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::put('/{id}/restore', [UsersController::class, 'restore'])->name('users.restore');
        Route::get('/{id}/delete', [UsersController::class, 'delete'])->name('users.delete'); // Ensure this route is defined
    });
    
    // Additional routes for user data export and printing
    Route::get('/export-data-users', [UsersController::class, 'exportUsers'])->name('users.export-data-users');
    Route::get('/export-excel-users', [UsersController::class, 'exportExcel'])->name('users.export-excel-users');
    Route::get('/print-users', [UsersController::class, 'printUsers'])->name('users.print-users');
    Route::get('/deleted-users', [UsersController::class, 'deletedUsers'])->name('users.deleted-users');


    //Shops
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

    Route::get('/export-data-shop', [ShopController::class, 'exportshop'])->name('seller.export-data-shop');
    Route::get('/export-excel-shop', [ShopController::class, 'exportexcel'])->name('seller.export-exce-shop');
    Route::get('/print-shop', [ShopController::class, 'printShop'])->name('seller.print-shop');
    Route::get('/add-shop', [ShopController::class, 'AddShop'])->name('seller.add-shop');

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

        Route::get('/export-data-product', [ProductsController::class, 'exportProduct'])->name('product.export-data-product');
        Route::get('/export-excel-product', [ProductsController::class, 'exportexcel'])->name('product.export-excel-product');
        Route::get('/print-product', [ProductsController::class, 'printProduct'])->name('product.print-product');
        Route::get('/add-product', [ProductsController::class, 'addProduct'])->name('product.add-product');

        Route::group(['prefix' => 'categories', 'middleware' => 'auth'], function () {
            Route::get('/', [MainCategoriesController::class, 'index'])->name('categories.index');
            Route::get('/create', [MainCategoriesController::class, 'create'])->name('categories.create');
            Route::post('/', [MainCategoriesController::class, 'store'])->name('categories.store');
            Route::get('/{id}', [MainCategoriesController::class, 'show'])->name('categories.show');
            Route::get('/{id}/edit', [MainCategoriesController::class, 'edit'])->name('categories.edit');
            Route::put('/{id}', [MainCategoriesController::class, 'update'])->name('categories.update');
            Route::delete('/{id}', [MainCategoriesController::class, 'destroy'])->name('categories.destroy');
            Route::put('/{id}/restore', [MainCategoriesController::class, 'restore'])->name('categories.restore');
        });
        
        Route::get('/export-data-categories', [MainCategoriesController::class, 'exportCategories'])->name('product.export-data-categories');
        Route::get('/export-excel-categories', [MainCategoriesController::class, 'exportexcel'])->name('product.export-excel-categories');
        Route::get('/print-categories', [MainCategoriesController::class, 'printCategories'])->name('product.print-categories');
        Route::get('/add-categories', [MainCategoriesController::class, 'addcategories'])->name('product.add-categories');
        Route::get('/deleted-categories', [MainCategoriesController::class, 'deletedCategories'])->name('product.deleted-categories');

        Route::group(['prefix' => 'sub-categories', 'middleware' => 'auth'], function () {
            Route::get('/', [SubCategoriesController::class, 'index'])->name('sub-categories.index');
            Route::get('/create', [SubCategoriesController::class, 'create'])->name('sub-categories.create');
            Route::post('/', [SubCategoriesController::class, 'store'])->name('sub-categories.store');
            Route::get('/{id}', [SubCategoriesController::class, 'show'])->name('sub-categories.show');
            Route::get('/{id}/edit', [SubCategoriesController::class, 'edit'])->name('sub-categories.edit');
            Route::put('/{id}', [SubCategoriesController::class, 'update'])->name('sub-categories.update');
            Route::delete('/{id}', [SubCategoriesController::class, 'destroy'])->name('sub-categories.destroy');
            Route::put('/{id}/restore', [SubCategoriesController::class, 'restore'])->name('sub-categories.restore');
        });
        
        Route::get('/export-data-sub-categories', [SubCategoriesController::class, 'exportsubCategories'])->name('product.export-data-sub-category');
        Route::get('/export-excel-sub-category', [SubCategoriesController::class, 'exportexcel'])->name('product.export-excel-sub-category');
        Route::get('/print-sub-categories', [SubCategoriesController::class, 'printsubCategories'])->name('product.print-sub-category');
        Route::get('/add-sub-categories', [SubCategoriesController::class, 'addsubcategories'])->name('product.add-sub-category');
        Route::get('/deleted-sub-categories', [SubCategoriesController::class, 'deletedSubCategories'])->name('product.deleted-sub-categories');
        
        Route::group(['prefix' => 'styles', 'middleware' => 'auth'], function () {
            Route::get('/', [StylesController::class, 'index'])->name('styles.index');
            Route::get('/create', [StylesController::class, 'create'])->name('styles.create');
            Route::post('/', [StylesController::class, 'store'])->name('styles.store');
            Route::get('/{id}', [StylesController::class, 'show'])->name('styles.show');
            Route::get('/{id}/edit', [StylesController::class, 'edit'])->name('styles.edit');
            Route::put('/{id}', [StylesController::class, 'update'])->name('styles.update');
            Route::delete('/{id}', [StylesController::class, 'destroy'])->name('styles.destroy');
            Route::post('/styles/{id}/restore', [StylesController::class, 'restore'])->name('styles.restore');

        });
        
        Route::get('/export-data-styles', [StylesController::class, 'exportStyle'])->name('product.export-data-styles');
        Route::get('/export-excel-styles', [StylesController::class, 'exportexcel'])->name('product.export-excel-styles');
        Route::get('/print-styles', [StylesController::class, 'printStyle'])->name('product.print-styles');
        Route::get('/add-styles', [StylesController::class, 'addstyle'])->name('product.add-styles');
        Route::get('/deleted-styles', [StylesController::class, 'deletedStyle'])->name('product.deleted-styles');


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

        Route::get('/export-data-preference', [PreferencesController::class, 'exportPreference'])->name('product.export-data-preference');
        Route::get('/export-excel-preference', [PreferencesController::class, 'exportexcel'])->name('product.export-excel-preference');
        Route::get('/print-preference', [PreferencesController::class, 'printPreference'])->name('product.print-preference');

        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', [OrdersController::class, 'index'])->name('orders.index');
            Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
            Route::post('/', [OrdersController::class, 'store'])->name('orders.store');
            Route::get('/{id}', [OrdersController::class, 'show'])->name('orders.show');
            Route::get('/{id}/edit', [OrdersController::class, 'edit'])->name('orders.edit');
            Route::put('/{id}', [OrdersController::class, 'update'])->name('orders.update');
            Route::delete('/{id}', [OrdersController::class, 'destroy'])->name('orders.destroy');

        });

        Route::get('/export-data-order', [OrdersController::class, 'exportOrder'])->name('product.export-data-order');
        Route::get('/export-excel-order', [OrdersController::class, 'exportexcel'])->name('product.export-excel-order');
        Route::get('/print-order', [OrdersController::class, 'printOrder'])->name('product.print-order');
        Route::get('/add-order', [OrdersController::class, 'addOrder'])->name('product.add-order');


        Route::group(['prefix' => 'order.histories'], function () {
            Route::get('/', [OrderHistoriesController::class, 'index'])->name('order.histories.index');
            Route::get('/create', [OrderHistoriesController::class, 'create'])->name('order.histories.create');
            Route::post('/', [OrderHistoriesController::class, 'store'])->name('order.histories.store');
            Route::get('/{id}', [OrderHistoriesController::class, 'show'])->name('order.histories.show');
            Route::get('/{id}/edit', [OrderHistoriesController::class, 'edit'])->name('order.histories.edit');
            Route::put('/{id}', [OrderHistoriesController::class, 'update'])->name('order.histories.update');
            Route::delete('/{id}', [OrderHistoriesController::class, 'destroy'])->name('order.histories.destroy');
        });

        Route::get('/export-data-order-histories', [OrderHistoriesController::class, 'exportOrderHistories'])->name('transaction.export-data-order-histories');
        Route::get('/export-excel-order-histories', [OrderHistoriesController::class, 'exportexcel'])->name('transaction.export-excel-order-histories');
        Route::get('/print-order-histories', [OrderHistoriesController::class, 'printOrderHistories'])->name('transaction.print-order-histories');
        Route::get('/add-order-histories', [OrderHistoriesController::class, 'addOrderHistories'])->name('transaction.add-order-histories');

        Route::group(['prefix' => 'complaints'], function () {
            Route::get('/', [ComplaintsController::class, 'index'])->name('complaints.index');
            Route::get('/create', [ComplaintsController::class, 'create'])->name('complaints.create');
            Route::post('/', [ComplaintsController::class, 'store'])->name('complaints.store');
            Route::get('/{id}', [ComplaintsController::class, 'show'])->name('complaints.show');
            Route::get('/{id}/edit', [ComplaintsController::class, 'edit'])->name('complaints.edit');
            Route::put('/{id}', [ComplaintsController::class, 'update'])->name('complaints.update');
            Route::delete('/{id}', [ComplaintsController::class, 'destroy'])->name('complaints.destroy');
        });

        Route::get('/export-data-complaint', [ComplaintsController::class, 'exportComplaints'])->name('transaction.export-data-complaint');
        Route::get('/export-excel-complaint', [ComplaintsController::class, 'exportexcel'])->name('transaction.export-excel-complaint');
        Route::get('/print-complaint', [ComplaintsController::class, 'printComplaints'])->name('transaction.print-complaint');
        Route::get('/add-complaint', [ComplaintsController::class, 'addComplaint'])->name('transaction.add-complaint');

        Route::group(['prefix' => 'refund-request'], function () {
            Route::get('/', [RefundRequestController::class, 'index'])->name('refund-request.index');
            Route::get('/create', [RefundRequestController::class, 'create'])->name('refund-request.create');
            Route::post('/', [RefundRequestController::class, 'store'])->name('refund-request.store');
            Route::get('/{id}', [RefundRequestController::class, 'show'])->name('refund-request.show');
            Route::get('/{id}/edit', [RefundRequestController::class, 'edit'])->name('refund-request.edit');
            Route::put('/{id}', [RefundRequestController::class, 'update'])->name('refund-request.update');
            Route::delete('/{id}', [RefundRequestController::class, 'destroy'])->name('refund-request.destroy');
            Route::post('/{id}/approve', [RefundRequestController::class, 'approve'])->name('refund-request.approve');
            Route::post('/{id}/reject', [RefundRequestController::class, 'reject'])->name('refund-request.reject');
        });
        
        Route::get('/export-refund-request', [RefundRequestController::class, 'exportRefundRequest'])->name('refund.export-refund-request');
        Route::get('/export-excel-refund-request', [RefundRequestController::class, 'exportexcel'])->name('refund.export-excel-refund-request');
        Route::get('/print-refund-request', [RefundRequestController::class, 'printRefundRequest'])->name('refund.print-refund-request');
        Route::get('/add-refund-request', [RefundRequestController::class, 'addRefundRequest'])->name('refund.add-refund-request');
        

    
        Route::group(['prefix' => 'developers'], function () {
            Route::get('/', [UsersController::class, 'developer'])->name('developers.index');
        });
        
    
});


