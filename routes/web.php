<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Hotel Manager Controllers
use App\Http\Controllers\HotelManager\DashboardController as HotelManagerDashboardController;
use App\Http\Controllers\HotelManager\HotelController as HotelManagerHotelController;
use App\Http\Controllers\HotelManager\RoomController as HotelManagerRoomController;
use App\Http\Controllers\HotelManager\GuestListController;
use App\Http\Controllers\HotelManager\HotelImageController;
use App\Http\Controllers\HotelManager\RoomController;
// Temple Manager Controllers
use App\Http\Controllers\TempleManager\DashboardController as TempleManagerDashboardController;
use App\Http\Controllers\TempleManager\TempleController as TempleManagerController;
use App\Http\Controllers\TempleManager\BookingController;
use App\Http\Controllers\TempleManager\SevaController as TempleManagerSevaController;
use App\Http\Controllers\TempleManager\DarshanSlotController as TempleManagerDarshanSlotController;
use App\Http\Controllers\TempleManager\DashboardController;
use App\Http\Controllers\TempleManager\SlotController;

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
Route::get('/profile/my-stays/{booking}/refund', [ProfileController::class, 'requestStayRefund'])->name('profile.my-stays.refund.request');
Route::post('/profile/my-stays/{booking}/refund', [ProfileController::class, 'storeStayRefundRequest'])->name('profile.my-stays.refund.store');
Route::post('/spiritual-help-request', [SpiritualHelpController::class, 'store'])->name('spiritual-help.store');
Route::get('/api/temples/{temple}/slots-for-date/{date}', [DarshanBookingController::class, 'getSlotsForDate'])->name('api.temples.slots_for_date');
// The URL the QR code points to
Route::get('/check-in/{token}', [CheckInController::class, 'show'])->name('check-in.show');

// The route that handles the confirmation button press
Route::post('/check-in/{token}', [CheckInController::class, 'confirm'])->name('check-in.confirm');
//  refund request form
Route::get('/profile/my-bookings/{booking}/refund', [ProfileController::class, 'requestRefund'])->name('profile.my-bookings.refund.request');

//the form submission
Route::post('/profile/my-bookings/{booking}/refund', [ProfileController::class, 'storeRefundRequest'])->name('profile.my-bookings.refund.store');
// FOR ACCOMMODATION BOOKINGS
Route::get('/profile/my-stays', [ProfileController::class, 'myStays'])->name('profile.my-stays.index');

// review page
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/about', [AboutController::class, 'about'])->name('about');
// This is the new route to handle the like functionality
Route::post('/reviews/{review}/like', [ReviewController::class, 'like'])->name('reviews.like');

// General Info Routes
Route::get('/info/faq', [GeneralInfoController::class, 'faq'])->name('info.faq');
Route::get('/info/dress-code', [GeneralInfoController::class, 'dressCode'])->name('info.dress-code');
Route::get('/info/contact', [GeneralInfoController::class, 'contact'])->name('info.contact');
Route::post('/contact-us', [GeneralInfoController::class, 'handleContactForm'])->name('info.contact.submit');

// A simple route for clearing the cart during testing
Route::get('/clear-cart', function() {
    session()->forget('cart');
    return '<h1>Cart has been cleared! You can now go back and test.</h1>';
});


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

// ## AUTHENTICATED USER ROUTES ##
Route::middleware('auth')->group(function () {
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
    // CORRECTED
    // In routes/web.php, inside your 'auth' middleware group

// This is the corrected route for your main bookings list
Route::get('/profile/my-bookings', [ProfileController::class, 'myBookings'])->name('profile.my-bookings.index');
Route::get('/profile/my-stays/{booking}/receipt', [ProfileController::class, 'downloadStayReceipt'])->name('profile.my-stays.receipt');
Route::delete('/profile/my-stays/{booking}/cancel', [ProfileController::class, 'cancelStayBooking'])->name('profile.my-stays.cancel');
// Add this new route to handle the receipt download for a specific booking
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
    });



// ## ADMIN, MANAGER, AND BREEZE AUTH ROUTES ##
require __DIR__.'/auth.php';

// ## ADMIN ROUTES ##
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
});
    // // Routes for Stay Refunds
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/stay-refund-requests', [BookingCancelController::class, 'index'])->name('booking-cancel.index');
    Route::get('/stay-refund-requests/{refundRequest}', [BookingCancelController::class, 'showStayRefund'])->name('booking-cancel.stay.show');
    Route::patch('/stay-refund-requests/{refundRequest}/status', [BookingCancelController::class, 'updateRefundStatus'])->name('booking-cancel.updateStatus');
});

// ## HOTEL MANAGER ROUTES ##
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
});

Route::middleware(['auth', 'role:temple_manager'])->prefix('temple-manager')->name('temple-manager.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Temple Management
    Route::get('/temple/edit', [TempleManagerController::class, 'edit'])->name('temple.edit');
    Route::put('/temple/update', [TempleManagerController::class, 'update'])->name('temple.update');

    // Slot and Seva Management
    Route::resource('slots', SlotController::class)->except(['show']);
    Route::resource('sevas', TempleManagerSevaController::class)->except(['show']);
    Route::get('slots/bulk-create', [SlotController::class, 'bulkCreate'])->name('slots.bulk-create');
    Route::post('slots/bulk-store', [SlotController::class, 'bulkStore'])->name('slots.bulk-store');
    // Booking Management Routes (Using the renamed BookingController)
    Route::get('bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{type}/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('bookings/{type}/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::get('slots/settings', [SlotController::class, 'settings'])->name('slots.settings');
    Route::post('slots/settings', [SlotController::class, 'updateSettings'])->name('slots.settings.update');
    Route::post('slots/day-status', [SlotController::class, 'updateDayStatus'])->name('slots.day-status.update');
    Route::delete('slots/day-status/{id}', [SlotController::class, 'deleteDayStatus'])->name('slots.day-status.delete');
});
