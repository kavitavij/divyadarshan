<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\TempleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuidelineController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DarshanBookingController;
use App\Http\Controllers\Admin\TempleController as AdminTempleController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\LatestUpdateController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;


// ## PUBLIC ROUTES ##
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
Route::get('/temples', [TempleController::class, 'index'])->name('temples.index');
Route::get('/temples/{temple}', [TempleController::class, 'show'])->name('temples.show');
Route::get('/guidelines', [GuidelineController::class, 'index'])->name('guidelines');
Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint.form');
Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');
Route::get('/darshan-booking', [DarshanBookingController::class, 'index'])->name('booking.index');
Route::match(['get', 'post'], '/booking/details', [DarshanBookingController::class, 'details'])->name('booking.details');
Route::post('/booking/confirm', [DarshanBookingController::class, 'store'])->name('booking.confirm');
Route::get('/booking/{booking}/summary', [DarshanBookingController::class, 'summary'])->name('booking.summary');


// ## AUTHENTICATED USER ROUTES ##
Route::middleware('auth')->group(function () {
    Route::post('/temples/{id}/favorite', [TempleController::class, 'favorite'])->name('temples.favorite');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/ebooks/{ebook}/purchase', [EbookController::class, 'purchase'])->name('ebooks.purchase');
    Route::get('/ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download');
    Route::get('/profile/my-ebooks', [ProfileController::class, 'myEbooks'])->name('profile.ebooks');
    Route::get('/profile/my-bookings', [ProfileController::class, 'myBookings'])->name('profile.bookings');

});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ## ADMIN ROUTES ##
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('temples', AdminTempleController::class);
    Route::resource('ebooks', AdminEbookController::class);
    Route::resource('latest_updates', LatestUpdateController::class);
    Route::resource('complaints', AdminComplaintController::class)->only(['index', 'destroy']);
    Route::patch('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::get('/temples/{temple}/slots', [AdminDarshanSlotController::class, 'index'])->name('temples.slots.index');
    Route::post('/temples/{temple}/slots', [AdminDarshanSlotController::class, 'store'])->name('temples.slots.store');
});


// ## BREEZE DASHBOARD & AUTHENTICATION ##
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';