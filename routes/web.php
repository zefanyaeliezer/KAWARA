<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

// Public News Portal (Default: Guest)
Route::get('/', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}/thumbnail', [NewsController::class, 'thumbnail'])->name('news.thumbnail');
Route::get('/berita/{news:slug}', [NewsController::class, 'show'])->name('news.show');

// Hidden Direct Admin Login (Cara 1)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.submit');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminNewsController::class, 'index'])->name('dashboard');
        Route::get('/berita/create', [AdminNewsController::class, 'create'])->name('news.create');
        Route::post('/berita', [AdminNewsController::class, 'store'])->name('news.store');
        Route::get('/berita/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
        Route::put('/berita/{news}', [AdminNewsController::class, 'update'])->name('news.update');
        Route::delete('/berita/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');
    });
});
