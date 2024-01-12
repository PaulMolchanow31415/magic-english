<?php

use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\UserAdministrationController;
use App\Http\Controllers\VocabularyCategoryController;

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
    'hasRole:'.Role::ADMIN,
])->name('admin.')->prefix("/admin")->group(function () {
    Route::resource('user', UserAdministrationController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('faq', FaqController::class)->only(['index', 'store', 'destroy']);

    Route::resource('vocabulary-category', VocabularyCategoryController::class)
        ->only(['index', 'show', 'store', 'destroy']);
});