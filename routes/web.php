<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AppointmentController;

/* Home page */
Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');

/* Admin login */
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

/* Appointments */
Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/check-time-availability/{barber}', [AppointmentController::class, 'checkTimeAvailability']);

Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/services', [DashboardController::class, 'services'])->name('admin.services');

    // Barbers
    Route::post('/admin/barber/store',
        [DashboardController::class, 'storeBarber'])->name('barber.store');
    Route::get('/admin/barber/edit/{id}',
        [DashboardController::class, 'editBarber'])->name('barber.edit');
    Route::put('/admin/barber/update/{id}',
        [DashboardController::class, 'updateBarber'])->name('barber.update');
    Route::delete('/admin/barber/delete/{id}',
        [DashboardController::class, 'deleteBarber'])->name('barber.delete');

    // Services
    Route::post('/admin/service/store',
        [DashboardController::class, 'storeService'])->name('service.store');
    Route::get('/admin/service/edit/{id}',
        [DashboardController::class, 'editService'])->name('service.edit');
    Route::put('/admin/service/update/{id}',
        [DashboardController::class, 'updateService'])->name('service.update');
    Route::delete('/admin/service/delete/{id}',
        [DashboardController::class, 'deleteService'])->name('service.delete');
});
