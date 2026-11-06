<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MeetingController;

// Landing Page Publik
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Dashboard Auth Breeze
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// User Profile
Route::middleware('auth')->group(function () {
    // Route Feed Mahasiswa
    Route::get('/feed', [PostController::class, 'index'])->name('feed.index');
    Route::post('/feed', [PostController::class, 'store'])->name('feed.store');
    Route::post('/feed/{post}/like', [PostController::class, 'toggleLike'])->name('feed.like');
    Route::post('/feed/{post}/comment', [PostController::class, 'storeComment'])->name('feed.comment');
    Route::delete('/feed/{post}', [PostController::class, 'destroy'])->name('feed.destroy');
    // Route Informasi Rapat
    Route::get('/meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::post('/meetings', [MeetingController::class, 'store'])->name('meetings.store');
});

// Admin & Pengurus Group
Route::middleware(['auth', 'role:super_admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', NewsController::class);
    Route::resource('events', EventController::class);
    Route::resource('galleries', GalleryController::class);
});

require __DIR__.'/auth.php';