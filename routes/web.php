<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/vehicles/{vehicle}', [SearchController::class, 'show'])->name('vehicles.show');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/compare', [CompareController::class, 'index'])->name('compare');
    Route::post('/compare', [CompareController::class, 'store'])->name('compare.store');
    Route::get('/history', [CompareController::class, 'history'])->name('history');
    Route::get('/history/{history}', [CompareController::class, 'show'])->name('history.show');
    Route::get('/history/{history}/pdf', [CompareController::class, 'pdf'])->name('history.pdf');
    Route::delete('/history/{history}', [CompareController::class, 'destroy'])->name('history.destroy');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/{vehicle}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/preferences', [ProfileController::class, 'preference'])->name('profile.preference');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('manufacturers', Admin\ManufacturerController::class)->except('show');
    Route::resource('vehicles', Admin\VehicleController::class)->except('show');
    Route::resource('users', Admin\UserController::class)->only(['index', 'update', 'destroy']);
    Route::resource('notifications', Admin\NotificationController::class)->except('show');
});
require __DIR__.'/auth.php';
