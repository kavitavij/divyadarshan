<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\TempleController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;


// Ebook
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Temples
Route::get('/temples', [TempleController::class, 'index'])->name('temples.index');
Route::get('/temples/{id}', [TempleController::class, 'show'])->name('temples.show');
Route::post('/temples/{id}/favorite', [TempleController::class, 'favorite'])
    ->name('temples.favorite')
    ->middleware('auth');


// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/temples/create', [TempleController::class, 'create'])->name('admin.temples.create');
    Route::post('/temples', [TempleController::class, 'store'])->name('admin.temples.store');
});

// Utility route to clear duplicates
Route::get('/clear-duplicate-temples', function () {
    $duplicates = DB::table('temples as t1')
        ->join('temples as t2', function ($join) {
            $join->on('t1.name', '=', 't2.name')
                 ->on('t1.location', '=', 't2.location')
                 ->whereRaw('t1.id > t2.id');
        })
        ->delete();
    return 'Duplicate temples deleted successfully.';
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
