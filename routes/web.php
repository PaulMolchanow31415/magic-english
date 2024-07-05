<?php

use App\Models\Faq;
use App\Models\Singer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\SingerController;
use App\Http\Controllers\EcommerceController;
use App\Http\Controllers\PurchasedController;
use App\Http\Controllers\SubscriberController;

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

Route::inertia('/', 'Welcome', ['faqs' => Faq::all()]);

Route::post('/accept-cookies', CookieController::class)->name('accept-cookies');

// Subscribing
Route::prefix("/subscribe")->name("subscribe.")->controller(SubscriberController::class)
    ->group(function () {
        Route::post('/store', 'store')->name('store')->middleware(['recaptcha', 'throttle:60,1']);

        Route::get('/is-subscribed', 'isSubscribed')->name('is-subscribed');

        Route::put("/current-user", 'changeSubscribingStatus')
            ->middleware(['throttle:10,1', 'verified', 'auth:sanctum'])
            ->name('change-status');
    });

// Singer and music
Route::prefix('/singer')->name('singer.')->group(function () {
    Route::inertia('/list', 'Music/Singers', ['singers' => Singer::all()])->name('list');

    Route::get('/{singer}', [SingerController::class, 'show'])->name('show');
});

// Ecommerce page
Route::get('/ecommerce', EcommerceController::class)->name('ecommerce');

// Cart
Route::prefix('/cart')->name('cart.')->controller(CartController::class)
    ->middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::post('/add-product/{product}', 'addProduct')->name('add-product');

        Route::get('/', 'show')->name('show');

        Route::delete('/remove-product/{product}', 'removeProduct')->name('remove-product');

        Route::delete('/clear', 'clearCart')->name('clear');

        Route::get('/checkout', 'checkout')->name('checkout');

        Route::get('/checkout/success', 'handleSuccessCheckout')->name('checkout.success');
    });

// Users purchased lessons
Route::prefix('/purchased')->name('purchased.')->controller(PurchasedController::class)
    ->middleware(['auth:sanctum'])->group(function () {
        Route::prefix('/lesson')->name('lesson.')->group(function () {
            Route::get('/all', 'lessons')->name('all');
        });
    });

require_once __DIR__.'/oauth2.php';
require_once __DIR__.'/authenticated.php';
require_once __DIR__."/admin.php";
require_once __DIR__."/fortify.php";
