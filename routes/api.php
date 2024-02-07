<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VocabularyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', fn() => request()->user());

Route::name('api.')->group(function () {
    Route::get('/translate', [VocabularyController::class, 'translate'])
        ->name('translate')
        ->middleware(['throttle:60,1']);

    Route::get('/vocabularies/{search}', [VocabularyController::class, 'list'])
        ->name('vocabulary.list');
});