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
use App\Http\Controllers\Admin\DarshanSlotController as AdminDarshanSlotController;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\Admin\SevaController as AdminSevaController;
use App\Http\Controllers\SevaBookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\HotelManager\DashboardController as HotelManagerDashboardController;
use App\Http\Controllers\HotelManager\HotelController as HotelManagerHotelController;
use App\Http\Controllers\HotelManager\RoomController as HotelManagerRoomController;
use App\Http\Controllers\HotelManager\GuestListController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\TempleManager\DashboardController as TempleManagerDashboardController;
use App\Http\Controllers\TempleManager\TempleController as TempleManagerController;
use App\Http\Controllers\TempleManager\DarshanBookingController as TempleManagerDarshanBookingController;
use App\Http\Controllers\TempleManager\SevaController as TempleManagerSevaController;
use App\Http\Controllers\TempleManager\DarshanSlotController as TempleManagerDarshanSlotController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StayBookingController;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PageController;
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');


// ## PUBLIC ROUTES
Route::post('/stays/confirm', [StayBookingController::class, 'confirm'])->name('stays.confirm');
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::post('/donations/confirm', [DonationController::class, 'confirm'])->name('donations.confirm');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
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
Route::post('/razorpay/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('razorpay.callback');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

Route::get('/payment/{type}/{id}', [PaymentController::class, 'create'])->name('payment.create')->middleware('auth');
Route::post('/payment/confirm', [PaymentController::class, 'confirm'])->name('payment.confirm')->middleware('auth');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/temple/dashboard', [TempleController::class, 'index'])->name('temple.dashboard');
Route::get('/hotel/dashboard', [HotelController::class, 'index'])->name('hotel.dashboard');
Route::get('/driver/dashboard', [DriverController::class, 'index'])->name('driver.dashboard');
Route::get('/home', [HomeController::class, 'index'])->name('home');



// ## AUTHENTICATED USER ROUTES ##
Route::middleware('auth')->group(function () {
    // Temple
    Route::post('/temples/{id}/favorite', [TempleController::class, 'favorite'])->name('temples.favorite');

    // Stays Booking
    Route::get('/stays/book/{room}', [StayController::class, 'details'])->name('stays.details');
    Route::post('/stays/book/{room}', [StayController::class, 'store'])->name('stays.store');
    Route::get('/stays/summary/{booking}', [StayController::class, 'summary'])->name('stays.summary');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ebooks
    Route::post('/ebooks/{ebook}/purchase', [EbookController::class, 'purchase'])->name('ebooks.purchase');
    Route::get('/ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download');
    Route::get('/profile/my-ebooks', [ProfileController::class, 'myEbooks'])->name('profile.ebooks');

    // My Bookings Page
    Route::get('/profile/my-bookings', [ProfileController::class, 'myBookings'])->name('profile.bookings');

    // Darshan Booking flow
    Route::get('/darshan-booking', [DarshanBookingController::class, 'index'])->name('booking.index');
    Route::post('/booking/details', [DarshanBookingController::class, 'details'])->name('booking.details');
    Route::post('/booking/store', [DarshanBookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/summary', [DarshanBookingController::class, 'summary'])->name('booking.summary');
    Route::post('/booking/confirm', [DarshanBookingController::class, 'confirmBooking'])->name('booking.confirm');

    // Seva Booking Flow
    Route::post('/seva-booking', [SevaBookingController::class, 'store'])->name('sevas.booking.store');
    Route::get('/seva-booking/{sevaBooking}/summary', [SevaBookingController::class, 'summary'])->name('sevas.booking.summary');
    Route::post('/seva-booking/confirm', [SevaBookingController::class, 'confirm'])->name('sevas.booking.confirm');

    //Donations
    Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
});

//## HOTEL MANAGER ROUTES ##
Route::middleware(['auth', 'role:hotel_manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    Route::get('/dashboard', [HotelManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/hotel/edit', [HotelManagerHotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotel', [HotelManagerHotelController::class, 'update'])->name('hotel.update');
    Route::resource('rooms', HotelManagerRoomController::class);
    Route::get('/guest-list', [GuestListController::class, 'index'])->name('guest-list.index');
});

// ## TEMPLE MANAGER ROUTES ##
Route::middleware(['auth', 'role:temple_manager'])->prefix('temple-manager')->name('temple-manager.')->group(function () {
    Route::get('/dashboard', [TempleManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/temple/edit', [TempleManagerController::class, 'edit'])->name('temple.edit');
    Route::put('/temple', [TempleManagerController::class, 'update'])->name('temple.update');
    Route::resource('slots', TempleManagerDarshanSlotController::class);
    Route::resource('sevas', TempleManagerSevaController::class);
    Route::get('/darshan-bookings', [TempleManagerDarshanBookingController::class, 'index'])->name('darshan-bookings.index');
});

// ## ADMIN ROUTES ##
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('temples', AdminTempleController::class);
    Route::resource('ebooks', AdminEbookController::class);
    Route::resource('latest_updates', LatestUpdateController::class);
    Route::resource('complaints', AdminComplaintController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::resource('temples.slots', AdminDarshanSlotController::class)->shallow();
    Route::resource('temples.sevas', AdminSevaController::class)->shallow();
    Route::get('/temples/{temple}/darshan-bookings', [AdminTempleController::class, 'showDarshanBookings'])->name('temples.darshan_bookings');
    Route::get('/temples/{temple}/seva-bookings', [AdminTempleController::class, 'showSevaBookings'])->name('temples.seva_bookings');
    Route::resource('hotels', App\Http\Controllers\Admin\HotelController::class);
    Route::resource('hotels.rooms', App\Http\Controllers\Admin\RoomController::class)->shallow();
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'destroy']);
});

// ## BREEZE DASHBOARD & AUTHENTICATION ##
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if (Auth::user()->role === 'hotel_manager') {
        return redirect()->route('hotel-manager.dashboard');
    }
    if (Auth::user()->role === 'temple_manager') {
        return redirect()->route('temple-manager.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
