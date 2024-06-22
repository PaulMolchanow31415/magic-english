<?php

use App\Role;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SingerController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GrammarController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\UserAdministrationController;

Route::middleware([
    ...config('auth.authenticated_permissions'),
    'hasRole:'.Role::ADMIN,
])->name('admin.')->prefix("/admin")->group(function () {
    // fortify не позволяет дополнительно настраивать других пользователей, поэтому ->
    Route::resource('user', UserAdministrationController::class)
        ->only(['index', 'store', 'destroy']);

    Route::resource('subscriber', SubscriberController::class)->only(['index', 'destroy']);

    Route::resource('faq', FaqController::class)->only(['index', 'store', 'destroy']);

    Route::name('vocabulary.')->prefix("/vocabulary")->group(function () {
        Route::put('/translation', [VocabularyController::class, 'deleteTranslation'])
            ->name('translation.destroy');

        // inertia не экранирует пути в параметрах запроса, что вызывает ошибку при delete методе
        Route::put('/poster', [VocabularyController::class, 'deletePoster'])
            ->name('poster.destroy');
    });

    Route::resource('vocabulary', VocabularyController::class)
        ->only(['index', 'show', 'store', 'destroy']);

    Route::patch('/report', [CommentController::class, 'report'])->name('comment.report');

    Route::resource('discussion', DiscussionController::class)->only(['index', 'destroy']);

    Route::resource('/dictionary', DictionaryController::class)
        ->only(['index', 'store', 'destroy']);

    Route::put('/dictionary/poster', [DictionaryController::class, 'deletePoster'])
        ->name('dictionary.delete-poster');

    Route::resource('/course', CourseController::class)
        ->only(['index', 'show', 'store', 'destroy']);

    Route::put('/course/poster', [CourseController::class, 'deletePoster'])
        ->name('course.delete-poster');

    Route::resource('/grammar', GrammarController::class)->only(['store', 'destroy']);

    Route::get('/grammar/show/{courseId}', [GrammarController::class, 'show'])
        ->name('grammar.show');

    Route::put('/grammar/change-order', [GrammarController::class, 'changeOrder'])
        ->name('grammar.change-order');

    Route::resource('/lesson', LessonController::class)->only(['index', 'store', 'destroy']);

    Route::resource('/product', ProductController::class)->only(['index', 'store', 'destroy']);

    Route::put('/product/poster', [ProductController::class, 'deletePoster'])
        ->name('product.delete-poster');

    Route::resource('/author', SingerController::class)->only(['index', 'store', 'destroy']);

    Route::put('/author/poster', [SingerController::class, 'deletePoster'])
        ->name('author-song.delete-poster');

    Route::resource('/music', MusicController::class)->only(['index', 'store', 'destroy']);

    Route::put('/music/delete-audio', [MusicController::class, 'deleteAudio'])
        ->name('music.delete-audio');
});