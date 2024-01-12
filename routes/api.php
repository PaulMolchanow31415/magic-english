<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/translate', function (Request $request) {
    $request->validate(['word' => 'string|required']);

    return Http::get(
        'https://api.mymemory.translated.net/get?q='.$request['word'].'&langpair=en|ru',
    )->json('matches');
})->name('api.translate');