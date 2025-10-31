<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\StayController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\TempleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuidelineController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\SevaController;
use App\Http\Controllers\SevaBookingController;
use App\Http\Controllers\DarshanBookingController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SpiritualHelpController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Auth\GoogleLoginController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TempleController as AdminTempleController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\LatestUpdateController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\DarshanSlotController as AdminDarshanSlotController;
use App\Http\Controllers\Admin\SevaController as AdminSevaController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\CancelledBookingController;
use App\Http\Controllers\Admin\BookingCancelController;
use App\Http\Controllers\Admin\SlotController as AdminSlotController;
use App\Http\Controllers\Admin\SpiritualHelpController as AdminSpiritualHelpController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminRevenueController;
use App\Http\Controllers\GoogleController;

// Hotel Manager Controllers
use App\Http\Controllers\HotelManager\DashboardController as HotelManagerDashboardController;
use App\Http\Controllers\HotelManager\HotelController as HotelManagerHotelController;
use App\Http\Controllers\HotelManager\RoomController as HotelManagerRoomController;
use App\Http\Controllers\HotelManager\GuestListController;
use App\Http\Controllers\HotelManager\HotelImageController;
use App\Http\Controllers\HotelManager\RoomController;
use App\Http\Controllers\HotelManager\RevenueController;
use App\Http\Controllers\HotelManager\RefundController;
use App\Http\Controllers\HotelManager\HotelManagerBookingController;

// Temple Manager Controllers
use App\Http\Controllers\TempleManager\DashboardController as TempleManagerDashboardController;
use App\Http\Controllers\TempleManager\TempleController as TempleManagerController;
use App\Http\Controllers\TempleManager\BookingController;
use App\Http\Controllers\TempleManager\SevaController as TempleManagerSevaController;
use App\Http\Controllers\TempleManager\DarshanSlotController as TempleManagerDarshanSlotController;
use App\Http\Controllers\TempleManager\DashboardController;
use App\Http\Controllers\TempleManager\SlotController;
use App\Http\Controllers\TempleManager\GalleryController;
use App\Http\Controllers\TempleManager\TempleRevenueController;
use App\Http\Controllers\TempleManager\NotificationController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ## PUBLIC ROUTES ##
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
Route::get('/temples', [TempleController::class, 'index'])->name('temples.index');
Route::get('/temples/{temple}', [TempleController::class, 'show'])->name('temples.show');
Route::post('/temples/details', [TempleController::class, 'details'])->name('temples.details');
Route::get('/guidelines', [GuidelineController::class, 'index'])->name('guidelines');
Route::get('/complaint', [ComplaintController::class, 'index'])->name('complaint.form');
Route::post('/complaint', [ComplaintController::class, 'store'])->name('complaint.store');
Route::get('/terms', [TermsController::class, 'index'])->name('terms');
Route::get('/sevas', [SevaController::class, 'index'])->name('info.sevas');
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::get('/cart/pay', [CartController::class, 'pay'])->name('cart.pay');
Route::post('/cart/payment-success', [CartController::class, 'paymentSuccess'])->name('cart.payment.success');
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::get('/about', [AboutController::class, 'about'])->name('about');
Route::get('/privacy-policy', [AboutController::class, 'privacy'])->name('info.privacy');
Route::get('/cancellation-policy', [AboutController::class, 'cancellation'])->name('info.cancellation');
Route::get('/profile/my-stays/{booking}/refund', [ProfileController::class, 'requestStayRefund'])->name('profile.my-stays.request-refund');
Route::post('/profile/my-stays/{booking}/refund', [ProfileController::class, 'storeStayRefundRequest'])->name('profile.my-stays.refund.store');
Route::post('/spiritual-help-request', [SpiritualHelpController::class, 'store'])->name('spiritual-help.store');
Route::get('/api/temples/{temple}/slots-for-date/{date}', [DarshanBookingController::class, 'getSlotsForDate'])->name('api.temples.slots_for_date');
Route::post('/stays/pay-now', [App\Http\Controllers\CartController::class, 'payNowStay'])->name('stays.payNow')->middleware('auth');
Route::post('/faq-submit', [FaqController::class, 'store'])->name('info.faq.submit');
Route::get('/spiritual-help', [SpiritualHelpController::class, 'create'])->name('spiritual-help.form');
Route::post('/spiritual-help-request', [SpiritualHelpController::class, 'store'])->name('spiritual-help.submit');
Route::post('/social-service-inquiry', [TempleController::class, 'storeSocialServiceInquiry'])->name('social.service.inquiry.store');

Route::get('/check-in/{token}', [CheckInController::class, 'show'])->name('check-in.show');

Route::post('/check-in/{token}', [CheckInController::class, 'confirm'])->name('check-in.confirm');
//  refund request form
Route::get('/profile/my-bookings/{booking}/refund', [ProfileController::class, 'requestRefund'])->name('profile.my-bookings.refund.request');

//the form submission
Route::post('/profile/my-bookings/{booking}/refund', [ProfileController::class, 'storeRefundRequest'])->name('profile.my-bookings.refund.store');
// FOR ACCOMMODATION BOOKINGS
Route::get('/profile/my-stays', [ProfileController::class, 'myStays'])->name('profile.my-stays.index');
Route::post('/book-pay-at-hotel', [CartController::class, 'bookPayAtHotel'])->name('stays.bookPayAtHotel')->middleware('auth');

// review page
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/about', [AboutController::class, 'about'])->name('about');
Route::post('/reviews/general', [ReviewController::class, 'storeGeneral'])
    ->name('reviews.store.general');
// This is the new route to handle the like functionality
Route::post('/reviews/{review}/like', [ReviewController::class, 'like'])->name('reviews.like');

// General Info Routes
Route::get('/info/faq', [GeneralInfoController::class, 'faq'])->name('info.faq');
Route::get('/info/dress-code', [GeneralInfoController::class, 'dressCode'])->name('info.dress-code');
Route::get('/info/contact', [GeneralInfoController::class, 'contact'])->name('info.contact');
Route::post('/contact-us', [GeneralInfoController::class, 'handleContactForm'])->name('info.contact.submit');

Route::get('/clear-cart', function() {
    session()->forget('cart');
    return '<h1>Cart has been cleared! You can now go back and test.</h1>';
});

// Language switch route: sets session and cookies (used to toggle server-side locale)
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'hi'])) {
        session(['locale' => $locale]);
        // persist cookie and Google Translate cookie for overlay compatibility
        return redirect()->back()
            ->withCookie(cookie()->forever('locale', $locale))
            ->withCookie(cookie()->forever('googtrans', '/en/' . $locale));
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/lang/test/{locale}', function ($locale, Request $request) {
    if (! in_array($locale, ['en', 'hi'])) {
        return response()->json(['error' => 'unsupported locale'], 400);
    }

    // set session and force locale for this request
    session(['locale' => $locale]);
    \Illuminate\Support\Facades\App::setLocale($locale);

    $payload = [
        'note' => 'Diagnostic: session and cookies set, App locale forced for this response',
        'app_locale_now' => app()->getLocale(),
        'lang_facade_locale' => \Illuminate\Support\Facades\Lang::getLocale(),
        'config_app_locale' => config('app.locale'),
        'cookie_locale' => $request->cookie('locale'),
        'session_locale' => session('locale'),
        'previous_url' => url()->previous(),
        'next_check' => url('/debug-locale'),
        'visit_about' => url('/about'),
    ];

    return response()->json($payload)
        ->withCookie(cookie()->forever('locale', $locale))
        ->withCookie(cookie()->forever('googtrans', '/en/' . $locale));
})->name('lang.test');

// DEBUG: show current app locale, request cookie and session value (dev only)
Route::get('/debug-locale', function (Request $request) {
    return [
        'app_locale' => app()->getLocale(),
        'lang_facade_locale' => \Illuminate\Support\Facades\Lang::getLocale(),
        'config_app_locale' => config('app.locale'),
        'cookie_locale' => $request->cookie('locale'),
        'session_locale' => session('locale'),
    ];
});

// web.php
// Route::get('/', function () {
//     return view('welcome'); // don't dd here
// });


// CART ROUTES
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'viewCart'])->name('view');
    Route::post('/add/seva', [CartController::class, 'addSeva'])->name('addSeva');
    Route::post('/add/ebook', [CartController::class, 'addEbook'])->name('addEbook');
    Route::post('/add/stay', [CartController::class, 'addStay'])->name('addStay');
    Route::post('/add-darshan', [CartController::class, 'addDarshan'])->name('addDarshan');
    Route::patch('/update/{index}', [CartController::class, 'updateQuantity'])->name('updateQuantity');
    Route::delete('/remove/{index}', [CartController::class, 'removeFromCart'])->name('remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/pay', [CartController::class, 'pay'])->name('pay');
   Route::post('/add-donation', [CartController::class, 'addDonation'])->name('addDonation');
});

Route::get('/auth/google/redirect', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

// ## AUTHENTICATED USER ROUTES ##
Route::middleware(['auth', 'verified'])->group(function () {
    // Role-based Dashboard Redirect
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'hotel_manager') return redirect()->route('hotel-manager.dashboard');
        if ($user->role === 'temple_manager') return redirect()->route('temple-manager.dashboard');
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/profile/my-bookings', [ProfileController::class, 'myBookings'])->name('profile.my-bookings.index');
    Route::get('/profile/my-stays/{booking}/receipt', [ProfileController::class, 'downloadStayReceipt'])->name('profile.my-stays.receipt');
    Route::delete('/profile/my-stays/{booking}/cancel', [ProfileController::class, 'cancelStayBooking'])->name('profile.my-stays.cancel');

    Route::get('/profile/bookings/{booking}/receipt', [ProfileController::class, 'downloadBookingReceipt'])->name('profile.my-bookings.receipt.download');
    Route::get('/profile/my-ebooks', [ProfileController::class, 'myEbooks'])->name('profile.ebooks');
    Route::get('/profile/my-donations', [ProfileController::class, 'myDonations'])->name('profile.my-donations.index');
    Route::delete('/profile/my-bookings/{booking}/cancel', [ProfileController::class, 'cancelBooking'])->name('profile.my-bookings.cancel');
    // Ebook Purchase & Download
    Route::post('/ebooks/{ebook}/purchase', [EbookController::class, 'purchase'])->name('ebooks.purchase');
    Route::get('/ebooks/{ebook}/download', [EbookController::class, 'download'])->name('ebooks.download');

    // Darshan Booking Flow
    Route::prefix('darshan-booking')->name('booking.')->group(function () {
        Route::get('/', [DarshanBookingController::class, 'index'])->name('index');
        Route::get('/details', [DarshanBookingController::class, 'details'])->name('details');
        Route::post('/store', [DarshanBookingController::class, 'store'])->name('store');
    });

    // Seva Booking Flow
    Route::prefix('seva-booking')->name('sevas.booking.')->group(function () {
        Route::get('/', [SevaBookingController::class, 'index'])->name('index');
        Route::post('/store', [SevaBookingController::class, 'store'])->name('store');
        Route::get('/{id}/summary', [SevaBookingController::class, 'summary'])->name('summary');
    });

    // Hotel/Stay Booking Flow
    Route::prefix('stays')->name('stays.')->group(function () {
        Route::get('/', [StayController::class, 'index'])->name('index');
        Route::get('/{hotel}', [StayController::class, 'show'])->name('show');
        Route::get('/room/{room}', [StayController::class, 'details'])->name('details');
        Route::post('/room/{room}/store', [StayController::class, 'store'])->name('store');
    });

    //MY-ORDERS
    Route::prefix('profile/my-orders')->name('profile.my-orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('download-invoice');
    });
        Route::get('/donations/{donation}/receipt', [ProfileController::class, 'downloadDonationReceipt'])->name('donations.receipt.download');
Route::get('/stays/bookings/{stayBooking}/review', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/stays/bookings/{stayBooking}/review', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{notificationId}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
});


require __DIR__.'/auth.php';

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::resource('temples', AdminTempleController::class);
    Route::get('/temples/{temple}/darshan-bookings', [AdminTempleController::class, 'showDarshanBookings'])->name('temples.darshan_bookings');
    Route::get('/temples/{temple}/seva-bookings', [AdminTempleController::class, 'showSevaBookings'])->name('temples.seva_bookings');
    Route::resource('temples.sevas', AdminSevaController::class)->shallow();
    Route::resource('ebooks', AdminEbookController::class);
    Route::resource('latest_updates', LatestUpdateController::class);
    Route::resource('complaints', AdminComplaintController::class)->only(['index', 'show', 'destroy']);
    Route::patch('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.updateStatus');
    Route::resource('hotels', AdminHotelController::class);
    Route::resource('hotels.rooms', AdminRoomController::class)->shallow();
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'destroy']);
    Route::get('/donations/export', [AdminDonationController::class, 'export'])->name('donations.export');
    Route::resource('donations', AdminDonationController::class)->only(['index', 'show']);
    Route::get('/bookings/view/{type}/{id}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::resource('slots', AdminSlotController::class)->except(['show']);
    Route::get('/accommodation-bookings', [AdminBookingController::class, 'accommodationIndex'])->name('bookings.accommodation');
    Route::get('/accommodation-bookings/{booking}', [AdminBookingController::class, 'showAccommodation'])->name('bookings.accommodation.show');
    Route::resource('spiritual-help', AdminSpiritualHelpController::class)->only(['index', 'destroy']);
    Route::resource('amenities', AmenityController::class);
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/revenue', [AdminRevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/download', [AdminRevenueController::class, 'download'])->name('revenue.download');
    Route::resource('managers', \App\Http\Controllers\Admin\ManagerController::class);
    Route::get('/announcements/create', [\App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
});
    //  Routes for Stay Refunds
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/stay-refund-requests', [BookingCancelController::class, 'index'])->name('booking-cancel.index');
    Route::get('/stay-refund-requests/{refundRequest}', [BookingCancelController::class, 'showStayRefund'])->name('booking-cancel.stay.show');
    Route::patch('/stay-refund-requests/{refundRequest}/status', [BookingCancelController::class, 'updateRefundStatus'])->name('booking-cancel.updateStatus');
    Route::get('/my-stays/{booking}/request-refund', [ProfileController::class, 'requestStayRefund'])->name('profile.my-stays.request-refund');
});

// HOTEL MANAGER ROUTES
Route::middleware(['auth', 'role:hotel_manager'])->prefix('hotel-manager')->name('hotel-manager.')->group(function () {
    Route::get('/dashboard', [HotelManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/hotel/edit', [HotelManagerHotelController::class, 'edit'])->name('hotel.edit');
    Route::put('/hotel', [HotelManagerHotelController::class, 'update'])->name('hotel.update');
    Route::resource('rooms', HotelManagerRoomController::class);
    Route::get('/guest-list', [GuestListController::class, 'index'])->name('guest-list.index');
    Route::get('/gallery', [HotelImageController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [HotelImageController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{image}', [HotelImageController::class, 'destroy'])->name('gallery.destroy');
    Route::delete('/rooms/photo/{photo}', [HotelManagerRoomController::class, 'deletePhoto'])->name('rooms.photo.delete');
    Route::patch('rooms/{room}/toggle-visibility', [RoomController::class, 'toggleVisibility'])->name('hotel-manager.rooms.toggleVisibility');
    Route::patch('rooms/{room}/toggle-visibility', [HotelManagerRoomController::class, 'toggleVisibility'])->name('rooms.toggleVisibility');
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/download', [RevenueController::class, 'download'])->name('revenue.download');
    Route::get('/refund', [RefundController::class, 'index'])->name('refund.index');
    Route::get('/refund-requests', [RefundController::class, 'index'])->name('refund.index');
    Route::get('/refund-requests/{booking}', [RefundController::class, 'show'])->name('refund.show');
    Route::patch('/refund-requests/{booking}/status', [RefundController::class, 'updateStatus'])->name('refund.updateStatus');
    Route::get('/bookings/{booking}', [HotelManagerBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [HotelManagerBookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/terms/edit', [App\Http\Controllers\HotelManager\TermsController::class, 'edit'])->name('terms.edit');
    Route::patch('/terms/update', [App\Http\Controllers\HotelManager\TermsController::class, 'update'])->name('terms.update');
    Route::get('/profile', [App\Http\Controllers\HotelManager\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\HotelManager\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile-photo', [App\Http\Controllers\HotelManager\ProfileController::class, 'removePhoto'])->name('profile.remove-photo');
    Route::get('/notifications', [App\Http\Controllers\HotelManager\NotificationController::class, 'showAll'])->name('notifications.all');
    Route::get('/notifications/unread', [App\Http\Controllers\HotelManager\NotificationController::class, 'fetchUnread'])->name('notifications.unread');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\HotelManager\NotificationController::class, 'markAsRead'])->name('notifications.read');
});

// TEMPLE MANAGER ROUTES
Route::middleware(['auth', 'role:temple_manager'])->prefix('temple-manager')->name('temple-manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/temple/edit', [TempleManagerController::class, 'edit'])->name('temple.edit');
    Route::put('/temple/update', [TempleManagerController::class, 'update'])->name('temple.update');
    Route::resource('slots', SlotController::class)->except(['show']);
    Route::resource('sevas', TempleManagerSevaController::class)->except(['show']);
    Route::get('slots/bulk-create', [SlotController::class, 'bulkCreate'])->name('slots.bulk-create');
    Route::post('slots/bulk-store', [SlotController::class, 'bulkStore'])->name('slots.bulk-store');
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{type}/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{type}/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('slots/settings', [SlotController::class, 'settings'])->name('slots.settings');
    Route::post('slots/settings', [SlotController::class, 'updateSettings'])->name('slots.settings.update');
    Route::post('slots/day-status', [SlotController::class, 'updateDayStatus'])->name('slots.day-status.update');
    Route::delete('slots/day-status/{id}', [SlotController::class, 'deleteDayStatus'])->name('slots.day-status.delete');
    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
    Route::post('gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('gallery/{image}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
    Route::get('/revenue', [TempleRevenueController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/download', [TempleRevenueController::class, 'downloadRevenueReport'])->name('revenue.download');
    Route::get('/profile', [App\Http\Controllers\TempleManager\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\TempleManager\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/notifications/all', [NotificationController::class, 'showAll'])->name('notifications.all');

    Route::get('/notifications/unread', [NotificationController::class, 'fetchUnread'])->name('notifications.unread');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
});
