<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('devices.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/devices', [AdminController::class, 'devices'])->name('devices');
    Route::get('/measurements', [AdminController::class, 'measurements'])->name('measurements');
    
    // Admin cihaz yönetimi
    Route::get('/devices/create', [AdminController::class, 'createDevice'])->name('devices.create');
    Route::post('/devices', [AdminController::class, 'storeDevice'])->name('devices.store');
    Route::get('/devices/{device}/edit', [AdminController::class, 'editDevice'])->name('devices.edit');
    Route::put('/devices/{device}', [AdminController::class, 'updateDevice'])->name('devices.update');
    Route::delete('/devices/{device}', [AdminController::class, 'destroyDevice'])->name('devices.destroy');
});

// User Device Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('devices', DeviceController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
