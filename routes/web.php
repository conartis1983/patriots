<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Middleware\IsAdmin;

Route::get('/', [HomeController::class, 'index'])->name('home');

// User Dashboard via Controller (besser für spätere Logik)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profil-Routen
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dashboard via Controller und Middleware (Laravel 12 Syntax)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Alle weiteren Admin-Routen hier hinein!
    Route::get('/admin/members', [MemberController::class, 'index'])->name('admin.members.index');
    Route::get('/admin/members/{user}/edit', [MemberController::class, 'edit'])->name('admin.members.edit');
    Route::put('/admin/members/{user}', [MemberController::class, 'update'])->name('admin.members.update');
    // Alternativ geht auch PATCH
    // Route::patch('/admin/members/{user}', [MemberController::class, 'update'])->name('admin.members.update');
});

require __DIR__.'/auth.php';