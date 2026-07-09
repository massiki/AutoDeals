<?php

use App\Http\Controllers\admin\CarController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\customer\CarController as CustomerCarController;
use App\Http\Controllers\customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\customer\InquiryController as CustomerInquiryController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::patch('/inquiries/{inquiry}', [AdminInquiryController::class, 'update'])->name('admin.inquiries.update');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
    Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
    Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
    Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
    Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');
});

Route::get('/cars', [CustomerCarController::class, 'index'])->name('cars.catalog');

Route::post('/inquiries', [CustomerInquiryController::class, 'store'])->name('inquiries.store');

Route::middleware(['auth'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard.customer');
    Route::get('/cars', [CustomerCarController::class, 'index'])->name('customer.cars');
    Route::get('/cars/{car}', [CustomerCarController::class, 'show'])->name('customer.cars.show');
    Route::get('/inquiries', [CustomerInquiryController::class, 'index'])->name('customer.inquiries');
});

require __DIR__ . '/auth.php';
