<?php

use App\Models\Faq;
use App\Models\Discussion;
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

Route::get('/', function () {
    return inertia('Welcome', ['faqs' => Faq::all()]);
});

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('subscribe')
    ->middleware(['recaptcha', 'throttle:60,1']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(
    function () {
        Route::get('/dashboard', fn() => inertia('Dashboard'))->name('dashboard');

        Route::prefix('/discussion')->name('discussion.')->middleware(['throttle:10,1'])->group(
            function () {
                Route::get('/{routeName}', [DiscussionController::class, 'show'])
                    ->name('show');

                Route::patch('/report', [CommentController::class, 'report'])
                    ->middleware(['allowed'])
                    ->name('report');
            },
        );

        Route::resource('comment', CommentController::class)
            ->middleware(['allowed', 'throttle:20,1'])
            ->only(['store', 'destroy']);
    },
);

require_once __DIR__."/admin.php";
require_once __DIR__."/fortify.php";
require_once __DIR__."/jetstream.php";