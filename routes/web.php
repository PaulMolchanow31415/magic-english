<?php

use App\Models\Faq;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\CookieController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\DictionaryController;

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

Route::prefix("/subscribe")->name("subscribe.")->controller(SubscriberController::class)
    ->group(function () {
        Route::post('/store', 'store')->name('store')->middleware(['recaptcha', 'throttle:60,1']);

        Route::get('/is-subscribed', 'isSubscribed')->name('is-subscribed');

        Route::put("/current-user", 'changeSubscribeStatus')
            ->middleware(['throttle:10,1', 'verified', 'auth:sanctum'])
            ->name('change-status');
    });

Route::middleware(config('auth.authenticated_permissions'))->group(function () {
    // Comments
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

    // Student
    Route::prefix('/student')->name('student.')->controller(StudentController::class)
        ->group(function () {
            Route::post('/add-vocabulary/{word}', 'addVocabulary')->name('add-vocabulary');

            Route::delete('/remove-vocabulary/{id}', 'removeVocabulary')->name('remove-vocabulary');

            Route::post('/complete-vocabularies', 'completeVocabularies')
                ->name('complete-vocabularies');

            Route::post('/add-dictionary/{id}', 'addDictionary')->name('add-dictionary');

            Route::delete('/flush-vocabularies', 'flushVocabularies')->name('flush-vocabularies');

            Route::post('/add-lesson/{id}', 'addLesson')->name('add-lesson');

            Route::post('/complete-lesson/{id}', 'completeLesson')->name('complete-lesson');

            Route::delete('/remove-lesson/{number}', 'removeLesson')->name('remove-lesson');

            Route::delete('/flush-lessons', 'flushLessons')->name('flush-lessons');

            Route::get('/latest-added-lesson', 'latestAddedLesson')->name('latest-added-lesson');

            Route::post('/add-course/{id}', 'addCourse')->name('add-course');

            Route::post('/complete-course/{id}', 'completeCourse')->name('complete-course');

            Route::prefix('/vocabularies')->name('vocabularies.')->group(function () {
                Route::get('/dashboard', 'showVocabularyDashboard')->name('dashboard');

                Route::get('/challenge', 'showVocabularyChallenge')->name('challenge');
            });

            Route::prefix('/courses')->name('courses.')->group(function () {
                Route::get('/dashboard', 'showCoursesDashboard')->name('dashboard');
            });

            Route::prefix('/lessons')->name('lessons.')->group(function () {
                Route::get('/dashboard', 'showLessonsDashboard')->name('dashboard');
            });
        });

    // Skill pages
    Route::prefix('/skills')->name('skills.')->group(function () {
        Route::prefix('/dictionaries')->controller(DictionaryController::class)->group(function () {
            Route::get('/', 'glossary')->name('glossary');

            Route::get('/{category}', 'show')->name('dictionary.show');
        });

        Route::prefix('/courses')->controller(CourseController::class)->group(function () {
            Route::get('/', 'courses')->name('courses');

            Route::get('/{name}', 'show')->name('course.show');
        });

        Route::prefix('/self-education')->controller(LessonController::class)->group(function () {
            Route::get('/', 'lessons')->name('self-education');

            Route::get('/{number}', 'show')->name('lesson.show');
        });
    });
});

Route::inertia('/ecommerce', 'Ecommerce/Index', [
    'title' => 'Тарифы',
])->name('ecommerce');

require_once __DIR__."/admin.php";
require_once __DIR__."/fortify.php";
require_once __DIR__."/jetstream.php";
