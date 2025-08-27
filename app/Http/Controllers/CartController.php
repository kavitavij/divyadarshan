<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seva;
use App\Models\Ebook;
use App\Models\Temple;
use App\Models\Room;
use App\Models\Booking;
use App\Models\SevaBooking;
use App\Models\StayBooking;
use App\Models\StayBookingGuest;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Exception;

class CartController extends Controller
{
    // Add Seva
    public function addSeva(Request $request)
    {
        $seva = Seva::findOrFail($request->seva_id);
        $cart = session()->get('cart', []);
        $cart[] = [
            'id' => $seva->id,
            'type' => 'seva',
            'name' => $seva->name,
            'price' => $seva->price,
            'quantity' => 1
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Seva added to cart!');
    }

    // Add Ebook
    public function addEbook(Request $request)
    {
        $ebook = Ebook::findOrFail($request->ebook_id);
        $cart = session()->get('cart', []);
        $cart[] = [
            'id' => $ebook->id,
            'type' => 'ebook',
            'name' => $ebook->title,
            'price' => $ebook->price,
            'quantity' => 1
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Ebook added to cart!');
    }

    // View cart
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    // Remove item
    public function removeFromCart($index)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$index])) unset($cart[$index]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item removed!');
    }
    public function updateQuantity(Request $request, $index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            $quantity = $request->input('quantity', 1);
            if ($quantity < 1) $quantity = 1;
            $cart[$index]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Cart updated!');
    }
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            // CORRECTED ROUTE NAME
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $totalAmount = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart.checkout', compact('cart', 'totalAmount'));
    }
    // public function pay()
    // {
    //     $cart = session()->get('cart', []);
    //     if (empty($cart)) {
    //         // CORRECTED ROUTE NAME
    //         return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
    //     }

    //     $totalAmount = collect($cart)->sum(function ($item) {
    //         return $item['price'] * $item['quantity'];
    //     });

    //     // Here integrate your payment gateway, and only after success:
    //     // session()->forget('cart');

    //     // CORRECTED ROUTE NAME
    //     return redirect()->route('cart.view')->with('success', "Payment of ₹$totalAmount completed!");
    // }

    public function addDarshan(Request $request)
    {
        $validatedData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'darshan_slot_id' => 'required|string',
            'selected_date' => 'required|date',
            'number_of_people' => 'required|integer|min:1',
            'devotees' => 'required|array',
            'devotees.*.first_name' => 'required|string|max:255',
            'devotees.*.last_name' => 'required|string|max:255',
            'devotees.*.age' => 'required|integer|min:1',
            'devotees.*.phone_number' => 'required|string|max:15',
            'devotees.*.id_type' => 'required|string',
            'devotees.*.id_number' => 'required|string',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.age' => 'required|integer|min:1',
            'guests.*.gender' => 'required|in:male,female,other',
            'guests.*.id_type' => 'required|in:aadhar,pan,passport',
            'guests.*.id_number' => 'required|string|max:50',
        ]);

        $temple = Temple::findOrFail($validatedData['temple_id']);
        $totalCharge = $temple->darshan_charge * $validatedData['number_of_people'];

        $cart = session()->get('cart', []);

        $cartItemId = 'darshan_' . $validatedData['temple_id'] . '_' . time();

        $cart[$cartItemId] = [
            'id'       => $cartItemId,
            'type'     => 'darshan',
            'name'     => 'Darshan Booking: ' . $temple->name,
            'price'    => $totalCharge,
            'quantity' => 1,
            'details'  => $validatedData
        ];

        session()->put('cart', $cart);

        // Redirects to the cart view page
        return redirect()->route('cart.view')->with('success', 'Darshan booking added to your cart!');
    }

    public function addStay(Request $request)
    {
        // 1. Validate the incoming request data
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'phone_number' => 'required|string|min:10',
            'guests' => 'required|array',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_type' => 'required|in:aadhar,pan,passport',
            'guests.*.id_number' => 'required|string|max:50',
        ]);

        $room = Room::with('hotel')->findOrFail($validated['room_id']);

        if ($validated['number_of_guests'] > $room->capacity) {
            return back()->with('error', 'Number of guests exceeds the room capacity of ' . $room->capacity)->withInput();
        }

        $checkIn = Carbon::parse($validated['check_in_date'])->startOfDay();
        $checkOut = Carbon::parse($validated['check_out_date'])->startOfDay();
        $nights = $checkIn->diffInDays($checkOut);


        if ($nights < 1) {
            return back()->with('error', 'DEBUG: Night calculation resulted in a non-positive number. Nights calculated: ' . $nights)->withInput();
        }

        $totalAmount = $nights * $room->price_per_night;


        $cart = session()->get('cart', []);


        $cartItemId = 'stay_' . $validated['room_id'] . '_' . time();

        // Add the stay booking details to the cart array
        $cart[$cartItemId] = [
            'id'        => $cartItemId,
            'type'      => 'stay',
            'name'      => $room->type . ' Room at ' . $room->hotel->name,
            'price'     => $totalAmount,
            'quantity'  => 1,
            'details'   => $validated,
            'room'      => $room->toArray(),
            'nights'    => $nights
        ];

        session()->put('cart', $cart);
           return redirect()->route('cart.view')
                     ->with('success', 'Item added to cart successfully!');
    }
    public function pay()
{
    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
    }

    $totalAmount = collect($cart)->sum(function ($item) {
        return $item['price'] * $item['quantity'];
    });

    // Razorpay expects amount in paise
    $amountInPaise = $totalAmount * 100;

    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    $order = $api->order->create([
        'receipt'         => 'order_' . time(),
        'amount'          => $amountInPaise,
        'currency'        => 'INR',
        'payment_capture' => 1,
    ]);

    return view('cart.razorpay', [
        'order'        => $order,
        'amount'       => $totalAmount,
        'razorpay_key' => config('services.razorpay.key')
    ]);
}

public function paymentSuccess(Request $request)
{
    // Save payment to DB if needed
    // session()->forget('cart');
    session()->forget('cart');
    return redirect()->route('cart.view')->with('success', 'Payment successful! Your order has been placed.');
}
    // public function paymentSuccess(Request $request)
    // {
    //     // 1. VALIDATE THE INCOMING RAZORPAY DATA
    //     $request->validate([
    //         'razorpay_payment_id' => 'required|string',
    //         'razorpay_order_id'   => 'required|string',
    //         'razorpay_signature'  => 'required|string',
    //     ]);

    //     // 2. ADD A CRITICAL AUTHENTICATION CHECK
    //     if (!Auth::check()) {
    //         // If no user is logged in, we cannot proceed.
    //         Log::error('Payment success callback received, but user is not authenticated.');
    //         return redirect()->route('login')->with('error', 'You must be logged in to complete a booking.');
    //     }

    //     $cart = session()->get('cart', []);
    //     if (empty($cart)) {
    //         // Handle case where cart is empty but callback is hit
    //         return redirect()->route('home')->with('info', 'Your session has expired, but your payment may have been processed.');
    //     }

    //     $totalAmount = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    //     $api = new Api(
    //             config('services.razorpay.key'),
    //             config('services.razorpay.secret')
    //         );

    //     try {
    //         // 3. VERIFY THE PAYMENT SIGNATURE
    //         $attributes = [
    //             'razorpay_order_id'   => $request->razorpay_order_id,
    //             'razorpay_payment_id' => $request->razorpay_payment_id,
    //             'razorpay_signature'  => $request->razorpay_signature
    //         ];
    //         $api->utility->verifyPaymentSignature($attributes);

    //         // 4. USE A DATABASE TRANSACTION TO CREATE BOOKINGS AND PAYMENT RECORD
    //         DB::transaction(function () use ($cart, $request, $totalAmount) {
    //             $userId = Auth::id(); // Get user ID once

    //             foreach ($cart as $item) {
    //                 if ($item['type'] === 'darshan') {
    //                     Booking::create([
    //                         'user_id'          => $userId,
    //                         'temple_id'        => $item['details']['temple_id'],
    //                         'darshan_slot_id'  => $item['details']['darshan_slot_id'],
    //                         'number_of_people' => $item['details']['number_of_people'],
    //                         'booking_date'     => $item['details']['selected_date'],
    //                         'status'           => 'confirmed',
    //                     ]);
    //                 } elseif ($item['type'] === 'seva') {
    //                     SevaBooking::create([
    //                         'user_id'      => $userId,
    //                         'seva_id'      => $item['id'],
    //                         'booking_date' => now(),
    //                         'status'       => 'confirmed',
    //                     ]);
    //                 } elseif ($item['type'] === 'stay') {
    //                     $stayBooking = StayBooking::create([
    //                         'user_id'        => $userId,
    //                         'room_id'        => $item['details']['room_id'],
    //                         'check_in_date'  => $item['details']['check_in_date'],
    //                         'check_out_date' => $item['details']['check_out_date'],
    //                         'total_amount'   => $item['price'],
    //                         'status'         => 'confirmed',
    //                     ]);

    //                     foreach ($item['details']['guests'] as $guestData) {
    //                         StayBookingGuest::create([
    //                             'stay_booking_id' => $stayBooking->id,
    //                             'name'            => $guestData['name'],
    //                             'age'             => $guestData['age'] ?? 0,
    //                             'gender'          => $guestData['gender'] ?? 'other',
    //                         ]);
    //                     }
    //                 } elseif ($item['type'] === 'ebook') {
    //                     Auth::user()->ebooks()->attach($item['id']);
    //                 }
    //             }

    //             Payment::create([
    //             'user_id'    => $userId,
    //             'type'       => 'booking',
    //             'reference_id' => null, // or link to a booking if needed
    //             'payment_id' => $request->razorpay_payment_id,
    //             'order_id'   => $request->razorpay_order_id,
    //             'signature'  => $request->razorpay_signature,
    //             'status'     => 'success', // ✅ match enum in migration
    //             'payload'    => $request->all(), // optional: store whole response
    //             'amount'     => $totalAmount,
    //         ]);
    //         });

    //         // 5. CLEAR THE CART AND REDIRECT ON SUCCESS
    //         session()->forget('cart'); // Forgetting just the cart is enough

    //         return redirect()->route('profile.my-bookings')->with('success', 'Payment successful! Your booking is confirmed.');

    //     } catch (Exception $e) {
    //         // If anything fails (signature verification, DB error), log it and redirect
    //         Log::error('Payment failed during processing: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString()
    //         ]);
    //         return redirect()->route('cart.view')->with('error', 'Payment failed during processing. Please contact support.');
    //     }
    // }
}
