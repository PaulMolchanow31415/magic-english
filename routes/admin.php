<?php

use App\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\UserAdministrationController;
use App\Http\Controllers\VocabularyCategoryController;

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
    'hasRole:'.Role::ADMIN,
])->name('admin.')->prefix("/admin")->group(function () {
    // fortify не позволяет дополнительно настраивать пользователей, поэтому ->
    Route::resource('user', UserAdministrationController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('faq', FaqController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('vocabulary-category', VocabularyCategoryController::class)
        ->only(['index', 'show', 'store', 'destroy']);

    Route::name('vocabulary.')->prefix("/vocabulary")->group(function () {
        Route::put('/translation', [VocabularyController::class, 'deleteTranslation'])
            ->name('translation.destroy');
        // inertia не экранирует пути, что вызывает ошибку при delete методе
        Route::put('/poster', [VocabularyController::class, 'deletePoster'])
            ->name('poster.destroy');
    });

    Route::resource('vocabulary', VocabularyController::class)
        ->only(['index', 'show', 'store', 'destroy']);
});