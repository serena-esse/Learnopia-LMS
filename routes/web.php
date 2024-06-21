<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Routes for all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/contact', [ContactController::class, 'showContactForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'submitContactForm'])->name('contact.submit');

    // Routes for course management, accessible to all authenticated users
    Route::resource('courses', CourseController::class)->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');


    // Nested resources for lessons and quizzes
    Route::prefix('courses/{course}')->group(function () {
        // Lessons
        Route::get('lessons', [LessonController::class, 'index'])->name('courses.lessons.index');
        Route::get('lessons/{lesson}', [LessonController::class, 'show'])->name('courses.lessons.show');

        // Quizzes
        Route::get('quizzes', [QuizController::class, 'index'])->name('courses.quizzes.index');
        Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('courses.quizzes.show');
        Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('courses.quizzes.submit');
    });
});

Route::middleware(['auth', 'verified', 'role:admin,teacher'])->group(function () {
    // Routes for creating and managing courses (admin and teacher roles only)
    Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::patch('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    Route::prefix('courses/{course}')->group(function () {
        // Lesson routes for admin and teacher
        Route::get('lessons/create', [LessonController::class, 'create'])->name('courses.lessons.create');
        Route::post('lessons', [LessonController::class, 'store'])->name('courses.lessons.store');
        Route::get('lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('courses.lessons.edit');
        Route::patch('lessons/{lesson}', [LessonController::class, 'update'])->name('courses.lessons.update');
        Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('courses.lessons.destroy');

        // Quiz routes for admin and teacher
        Route::get('quizzes/create', [QuizController::class, 'create'])->name('courses.quizzes.create');
        Route::post('quizzes', [QuizController::class, 'store'])->name('courses.quizzes.store');
        Route::get('quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('courses.quizzes.edit');
        Route::patch('quizzes/{quiz}', [QuizController::class, 'update'])->name('courses.quizzes.update');
        Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy'])->name('courses.quizzes.destroy');
    });
});

require __DIR__.'/auth.php';
