<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StayController;
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
use App\Http\Controllers\Admin\DarshanSlotController;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\Admin\SevaController;
use App\Http\Controllers\SevaBookingController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\HotelManager\DashboardController;
use App\Http\Controllers\HotelManager\HotelController;
use App\Http\Controllers\HotelManager\RoomController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\HotelManager\GuestListController;
use App\Http\Controllers\Admin\ContactSubmissionController;

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
Route::get('/info/faq', [GeneralInfoController::class, 'faq'])->name('info.faq');
Route::get('/info/dress-code', [GeneralInfoController::class, 'dressCode'])->name('info.dress-code');
Route::get('/info/contact', [GeneralInfoController::class, 'contact'])->name('info.contact');
Route::get('/info/sevas', [GeneralInfoController::class, 'sevas'])->name('info.sevas');
Route::get('/seva-booking', [SevaBookingController::class, 'index'])->name('sevas.booking.index');
Route::get('/darshan-booking', [DarshanBookingController::class, 'index'])->name('booking.index');
Route::post('/contact-us', [GeneralInfoController::class, 'handleContactForm'])->name('info.contact.submit');
Route::get('/stays', [StayController::class, 'index'])->name('stays.index');
Route::get('/stays/{hotel}', [StayController::class, 'show'])->name('stays.show');
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [App\Http\Controllers\DonationController::class, 'store'])->name('donations.store');
Route::get('/payment', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/stays/book/{room}', [StayController::class, 'details'])->name('stays.details');
Route::post('/stays/book/{room}', [StayController::class, 'store'])->name('stays.store');
Route::get('/stays/summary/{booking}', [StayController::class, 'summary'])->name('stays.summary');
Route::get('/payment/create/{id}/{type}', [PaymentController::class, 'create'])->name('payment.create');

// ## AUTHENTICATED USER ROUTES ##
Route::middleware('auth')->group(function () {
    // Temple
    Route::post('/temples/{id}/favorite', [TempleController::class, 'favorite'])->name('temples.favorite');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ebooks
    Route::post('/ebooks/{ebook}/purchase', [EbookController::class, 'purchase'])->name('ebooks.purchase');
    Route::get('/ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download');
    Route::get('/profile/my-ebooks', [ProfileController::class, 'myEbooks'])->name('profile.ebooks');
    Route::post('/ebooks/confirm-purchase', [App\Http\Controllers\EbookController::class, 'confirmPurchase'])->name('ebooks.confirmPurchase');

    // Bookings
    Route::get('/profile/my-bookings', [ProfileController::class, 'myBookings'])->name('profile.bookings');

    // Darshan Booking flow
    Route::match(['get', 'post'], '/booking/details', [DarshanBookingController::class, 'details'])->name('booking.details');
    Route::post('/booking/confirm', [DarshanBookingController::class, 'store'])->name('booking.confirm');
    Route::get('/booking/{booking}/summary', [DarshanBookingController::class, 'summary'])->name('booking.summary');
    Route::get('/booking/{booking}/payment', [DarshanBookingController::class, 'payment'])->name('booking.payment');

    // Seva Booking Flow
    Route::post('/seva-booking', [SevaBookingController::class, 'store'])->name('sevas.booking.store');
    Route::get('/seva-booking/{sevaBooking}/summary', [SevaBookingController::class, 'summary'])->name('sevas.booking.summary');
    Route::get('/seva-booking/{sevaBooking}/payment', [SevaBookingController::class, 'payment'])->name('sevas.booking.payment');
    Route::post('/seva-booking/confirm', [SevaBookingController::class, 'confirm'])->name('sevas.booking.confirm');

    //Donations
    Route::get('/donations/{donation}/payment', [App\Http\Controllers\DonationController::class, 'payment'])->name('donations.payment');
    Route::post('/donations/confirm', [App\Http\Controllers\DonationController::class, 'confirm'])->name('donations.confirm');
});

//## HOTEL MANAGER ROUTES ##
Route::middleware(['auth', 'role:hotel_manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hotel/edit', [HotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotel', [HotelController::class, 'update'])->name('hotel.update');
    Route::resource('rooms', RoomController::class);
    Route::get('/guest-list', [GuestListController::class, 'index'])->name('guest-list.index');
});

// ## ADMIN ROUTES ##
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('temples', AdminTempleController::class);
    Route::resource('ebooks', AdminEbookController::class);
    Route::resource('latest_updates', LatestUpdateController::class);
    Route::resource('complaints', AdminComplaintController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::resource('temples.slots', DarshanSlotController::class)->shallow();
    Route::resource('temples.sevas', SevaController::class)->shallow();
    Route::get('/temples/{temple}/darshan-bookings', [AdminTempleController::class, 'showDarshanBookings'])->name('temples.darshan_bookings');
    Route::get('/temples/{temple}/seva-bookings', [AdminTempleController::class, 'showSevaBookings'])->name('temples.seva_bookings');
    Route::resource('hotels', App\Http\Controllers\Admin\HotelController::class);
    Route::resource('hotels.rooms', App\Http\Controllers\Admin\RoomController::class)->shallow();
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'destroy']);
});


// ## BREEZE DASHBOARD & AUTHENTICATION ##
Route::get('/dashboard', function () {
    // THE FIX: Changed auth()->user() to Auth::user()
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Add this line if your hotel managers should also be redirected
    if (Auth::user()->role === 'hotel_manager') {
        return redirect()->route('hotel-manager.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
