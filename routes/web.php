<?php

use App\Models\Faq;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\DiscussionController;

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

Route::prefix("/subscribe")->name("subscribe.")->group(function () {
    Route::post('/store', [SubscriberController::class, 'store'])
        ->name('store')
        ->middleware(['recaptcha', 'throttle:60,1']);

    Route::get('/is-subscribed', [SubscriberController::class, 'isSubscribed'])
        ->name('is-subscribed');

    Route::put("/current-user", [SubscriberController::class, 'changeSubscribeStatus'])
        ->middleware(['throttle:10,1', 'verified', 'auth:sanctum'])
        ->name('change-status');
});

Route::middleware(config('auth.authenticated_permissions'))->group(function () {
    // todo: remove
    Route::get('/dashboard', fn() => inertia('Dashboard'))->name('dashboard');

    Route::prefix('/discussion')->name('discussion.')->group(function () {
        Route::get('/{id}', [DiscussionController::class, 'show'])
            ->name('show');

        Route::patch('/report', [CommentController::class, 'report'])
            ->middleware(['allowed', 'throttle:10,1'])
            ->name('report');
    });

    Route::resource('comment', CommentController::class)
        ->middleware(['allowed', 'throttle:20,1'])
        ->only(['store', 'destroy']);
});

require_once __DIR__."/admin.php";
require_once __DIR__."/fortify.php";
require_once __DIR__."/jetstream.php";
