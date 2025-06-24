<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\EventController;
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

// Admin-Routen: alles unter /admin, geschützt durch IsAdmin-Middleware
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Mitgliederverwaltung
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{user}', [MemberController::class, 'update'])->name('members.update');

    // Eventverwaltung (Resource Controller)
    Route::resource('events', EventController::class);

    // Ticketkontingente
    Route::get('ticket-quotas', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'index'])->name('ticket-quotas.index');
    Route::get('ticket-quotas/create', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'create'])->name('ticket-quotas.create');
    Route::post('ticket-quotas', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'store'])->name('ticket-quotas.store');
    Route::get('ticket-quotas/{ticketQuota}/edit', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'edit'])->name('ticket-quotas.edit');
    Route::put('ticket-quotas/{ticketQuota}', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'update'])->name('ticket-quotas.update');
    Route::delete('ticket-quotas/{ticketQuota}', [\App\Http\Controllers\Admin\TicketQuotaController::class, 'destroy'])->name('ticket-quotas.destroy');
});

require __DIR__.'/auth.php';