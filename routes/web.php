<?php

use App\Models\Faq;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return inertia('Welcome', ['faqs' => Faq::all()]);
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])
    ->group(function () {
        Route::get('/dashboard', function () {
            return inertia('Dashboard');
        })->name('dashboard');
    });

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('subscribe')
    ->middleware(['recaptcha', 'throttle:60,1']);

require_once __DIR__."/admin.php";
require_once __DIR__."/fortify.php";
require_once __DIR__."/jetstream.php";