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
use App\Mail\StayBookingNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\Devotee;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Str;
use App\Models\DefaultDarshanSlot;
use App\Models\DarshanSlot;
use App\Mail\OrderConfirmation;
class CartController extends Controller
{
    public function addSeva(Request $request)
    {
        $seva = Seva::findOrFail($request->seva_id);
        $cart = session()->get('cart', []);
        $cart['seva_' . $seva->id] = [
            'id' => $seva->id,
            'type' => 'seva',
            'name' => $seva->name,
            'price' => $seva->price,
            'quantity' => 1
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Seva added to cart!');
    }
    public function addEbook(Request $request)
    {
        $ebook = Ebook::findOrFail($request->ebook_id);
        $cart = session()->get('cart', []);

        $cartItemData = [
            'id' => $ebook->id,
            'type' => 'ebook',
            'name' => $ebook->title,
            'price' => $ebook->discounted_price,
            'quantity' => 1
        ];

        if ($ebook->discount_percentage > 0) {
            $cartItemData['original_price'] = $ebook->price;
        }

        $cart['ebook_' . $ebook->id] = $cartItemData;
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Ebook added to cart!');
    }

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
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
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $totalAmount = collect($cart)->sum(function ($item) {
            // Use the same logic as your cart view for accurate totals
            if ($item['type'] === 'seva' || $item['type'] === 'ebook') {
                return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
            }
            return $item['price'] ?? 0;
        });

        if ($totalAmount <= 0) {
            return redirect()->route('cart.view')->with('error', 'Nothing to pay.');
        }

        $amountInPaise = $totalAmount * 100;
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $order = $api->order->create([
                'receipt'         => 'order_' . time() . '_' . Str::random(5),
                'amount'          => $amountInPaise,
                'currency'        => 'INR',
                'payment_capture' => 1,
            ]);
        } catch (Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
            return redirect()->route('cart.view')->with('error', 'Could not connect to payment gateway. Please try again.');
        }

        // Pass the Razorpay order details directly to the razorpay view
        return view('cart.razorpay', [
            'order'        => $order,
            'amount'       => $totalAmount,
            'razorpay_key' => config('services.razorpay.key')
        ]);
    }
    public function addDarshan(Request $request)
    {
        $validatedData = $request->validate([
            'temple_id' => 'required|exists:temples,id',
            'selected_date' => 'required|date',
            'number_of_people' => 'required|integer|min:1',
            'devotees' => 'required|array',
            'devotees.*.full_name' => 'required|string|max:255',
            'devotees.*.age' => 'required|integer|min:1',
            'devotees.*.gender' => 'required|string',
            'devotees.*.email' => 'required|email',
            'devotees.*.phone_number' => 'required|string|max:15',
            'devotees.*.pincode' => 'required|string|max:6',
            'devotees.*.city' => 'required|string',
            'devotees.*.state' => 'required|string',
            'devotees.*.address' => 'required|string',
            'devotees.*.id_type' => 'required|string',
            'devotees.*.id_number' => 'required|string',
            'devotees.*.id_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'darshan_slot_id' => ['required','string',
                // Custom rule to validate both 'default-ID' and regular IDs
                function ($attribute, $value, $fail) {
                    if (str_starts_with($value, 'default-')) {
                        $id = (int) str_replace('default-', '', $value);
                        if (!DB::table('default_darshan_slots')->where('id', $id)->exists()) {
                            $fail('The selected darshan slot is invalid.');
                        }
                    } else {
                        if (!DB::table('darshan_slots')->where('id', $value)->exists()) {
                            $fail('The selected darshan slot is invalid.');
                        }
                    }
                },
            ],
        ]);

        // 2. HANDLE FILE UPLOADS BEFORE SAVING TO SESSION
        $processedDevotees = [];
        foreach ($validatedData['devotees'] as $index => $devotee) {
            if ($request->hasFile("devotees.{$index}.id_photo")) {
                // Store the file in 'storage/app/public/id_proofs'
                $path = $request->file("devotees.{$index}.id_photo")->store('id_proofs', 'public');
                $devotee['id_photo_path'] = $path;
                unset($devotee['id_photo']);
            }
            $processedDevotees[] = $devotee;
        }
        $validatedData['devotees'] = $processedDevotees;

        // 3. ADD TO CART LOGIC (Now using processed data)
        $temple = Temple::findOrFail($validatedData['temple_id']);
        $totalCharge = $temple->darshan_charge * $validatedData['number_of_people'];
        $cart = session()->get('cart', []);
        $cartItemId = 'darshan_' . $validatedData['temple_id'] . '_' . time();

        $cart[$cartItemId] = [
            'id'       => $cartItemId,
            'type'     => 'darshan',
            'name'     => 'Darshan at ' . $temple->name,
            'price'    => $totalCharge,
            'quantity' => 1,
            'details'  => $validatedData
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Darshan booking added to your cart!');
    }
    public function addStay(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'email' => 'required|email|max:255',
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
            return back()->with('error', 'Check-out date must be at least one day after check-in.')->withInput();
        }

        $totalAmount = $nights * $room->discounted_price;
        $cart = session()->get('cart', []);
        $cartItemId = 'stay_' . $validated['room_id'] . '_' . time();

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
        return redirect()->route('cart.view')->with('success', 'Item added to cart successfully!');
    }
    public function bookPayAtHotel(Request $request)
    {
        // 1. Validate the incoming data (same as adding to cart)
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|min:10',
            'guests' => 'required|array',
            'guests.*.name' => 'required|string|max:255',
            'guests.*.id_type' => 'required|in:aadhar,pan,passport',
            'guests.*.id_number' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        $room = \App\Models\Room::findOrFail($validated['room_id']);

        // Calculate total amount
        $checkIn = new \DateTime($validated['check_in_date']);
        $checkOut = new \DateTime($validated['check_out_date']);
        $nights = $checkOut->diff($checkIn)->days;
        $totalAmount = $nights * $room->discounted_price;

        $stayBooking = null; // Define variable to use outside the transaction

        // 2. Use a Database Transaction to ensure data integrity
        DB::transaction(function () use ($validated, $user, $room, $totalAmount, &$stayBooking) {
            // Create a parent Order record
            $order = Order::create([
                'user_id'       => $user->id,
                'order_number'  => 'DD-' . strtoupper(Str::random(10)),
                'total_amount'  => $totalAmount,
                'status'        => 'Payment Pending', // A new status for the main order
                'payment_id'    => null,
                'order_details' => [$validated], // Save details for reference
            ]);

            // 3. Create the StayBooking with the new payment status
            $stayBooking = StayBooking::create([
                'user_id'          => $user->id,
                'order_id'         => $order->id,
                'room_id'          => $validated['room_id'],
                'hotel_id'         => $room->hotel_id,
                'check_in_date'    => $validated['check_in_date'],
                'check_out_date'   => $validated['check_out_date'],
                'number_of_guests' => $validated['number_of_guests'],
                'email'            => $validated['email'],
                'phone_number'     => $validated['phone_number'],
                'total_amount'     => $totalAmount,
                'status'           => 'Confirmed',
                'payment_method'   => 'pay_at_hotel',
                'payment_status'   => 'unpaid',
            ]);

            // Save guest details
            foreach ($validated['guests'] as $guestData) {
                $stayBooking->guests()->create($guestData);
            }
        });

        // 4. Send the notification email to the hotel manager
        if ($stayBooking) {
            try {
                $bookingWithDetails = StayBooking::with('room.hotel.user')->find($stayBooking->id);
                // This is the UPDATED code
                if ($bookingWithDetails && $bookingWithDetails->room->hotel->user) {
                    $manager = $bookingWithDetails->room->hotel->user;

                    // Send the original email notification
                    Mail::to($manager->email)->send(new StayBookingNotification($bookingWithDetails));

                    // ALSO send the new database notification for the bell icon
                    $manager->notify(new \App\Notifications\NewHotelBooking($bookingWithDetails));
                }
            } catch (Exception $e) {
                Log::error("Failed to send pay-at-hotel notification for booking ID {$stayBooking->id}: " . $e->getMessage());
            }
        }

        // 5. Redirect with a success message
        return redirect()->route('profile.my-orders.index')->with('success', 'Your booking is confirmed! Please complete your payment at the hotel.');
    }
    public function pay(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $totalAmount = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

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
        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'  => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $user = Auth::user();
        if (session()->has('direct_checkout_cart')) {
        $cart = session()->get('direct_checkout_cart');
        session()->forget('direct_checkout_cart');
    } else {
        $cart = session()->get('cart', []);
        session()->forget('cart');
    }
        if (empty($cart) || !$user) {
            return redirect()->route('home')->with('error', 'Your session has expired or you are not logged in.');
        }

        $totalAmount = collect($cart)->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $api->utility->verifyPaymentSignature($validated);
            Log::info('Razorpay signature verified for order: ' . $validated['razorpay_order_id']);

            $finalOrder = null;

            DB::transaction(function () use ($cart, $validated, $totalAmount, $user, &$finalOrder) {
                $order = Order::create([
                    'user_id'       => $user->id,
                    'order_number'  => 'DD-' . strtoupper(Str::random(10)),
                    'total_amount'  => $totalAmount,
                    'status'        => 'Completed',
                    'payment_id'    => $validated['razorpay_payment_id'],
                    'order_details' => $cart,
                ]);

                foreach ($cart as $item) {
                    if ($item['type'] === 'darshan') {
                        $details = $item['details'];
                        $slotIdString = $details['darshan_slot_id'];
                        $isDefaultSlot = str_starts_with($slotIdString, 'default-');
                        $slotId = $isDefaultSlot ? (int)str_replace('default-', '', $slotIdString) : (int)$slotIdString;

                        $booking = Booking::create([
                            'user_id' => $user->id,
                            'order_id' => $order->id,
                            'temple_id' => $details['temple_id'],
                            'booking_date' => $details['selected_date'],
                            'number_of_people' => $details['number_of_people'],
                            'status' => 'Confirmed',
                            'check_in_token' => Str::uuid(),
                            'darshan_slot_id' => $isDefaultSlot ? null : $slotId,
                            'default_darshan_slot_id' => $isDefaultSlot ? $slotId : null,
                        ]);

                        foreach ($details['devotees'] as $devoteeData) {
                            $booking->devotees()->create($devoteeData);
                        }

                        if (!$isDefaultSlot) {
                            $slot = DarshanSlot::find($slotId);
                            if ($slot) {
                                $slot->increment('booked_capacity', $details['number_of_people']);
                            }
                        }
                    }
                    else if ($item['type'] === 'stay') {
                        $details = $item['details'];
                        $roomData = $item['room'];

                        // 1. Create the StayBooking
                        $stayBooking = StayBooking::create([
                            'user_id'          => $user->id,
                            'order_id'         => $order->id,
                            'room_id'          => $details['room_id'],
                            'hotel_id'         => $roomData['hotel_id'],
                            'check_in_date'    => $details['check_in_date'],
                            'check_out_date'   => $details['check_out_date'],
                            'number_of_guests' => $details['number_of_guests'],
                            'email'            => $details['email'],
                            'phone_number'     => $details['phone_number'],
                            'total_amount'     => $item['price'],
                            'status'           => 'Confirmed',
                        ]);

                        foreach ($details['guests'] as $guestData) {
                            $stayBooking->guests()->create($guestData);
                        }

                        try {
                            // Load the booking with all its relationships to get the manager's user info
                            $bookingWithDetails = StayBooking::with('room.hotel.user')->find($stayBooking->id);

                            // Check if the manager's user record exists
                            // This is the UPDATED code
                            if ($bookingWithDetails && $bookingWithDetails->room->hotel->user) {
                                $manager = $bookingWithDetails->room->hotel->user;
                                
                                // Send the original email notification
                                Mail::to($manager->email)->send(new StayBookingNotification($bookingWithDetails));
                                
                                // ALSO send the new database notification for the bell icon
                                $manager->notify(new \App\Notifications\NewHotelBooking($bookingWithDetails));
                            } else {
                                Log::error("Could not find manager email for stay booking ID: {$stayBooking->id}");
                            }
                        } catch (Exception $e) {
                            // Log the error if mail fails, but don't crash the user's booking confirmation
                            Log::error("Failed to send stay booking notification for booking ID {$stayBooking->id}: " . $e->getMessage());
                        }
                    }
                    // Handle Seva
                    else if ($item['type'] === 'seva') {
                        SevaBooking::create([
                            'user_id'  => $user->id,
                            'order_id' => $order->id,
                            'seva_id'  => $item['id'],
                            'amount'   => $item['price'],
                            'quantity' => $item['quantity'],
                            'status'   => 'Completed',
                        ]);
                    }
                    // Handle eBook
                    else if ($item['type'] === 'ebook') {
                        $user->ebooks()->attach($item['id']);
                    }
                    // Handle Donation
                    else if ($item['type'] === 'donation') {
                        Donation::create([
                            'user_id'          => $user->id,
                            'order_id'         => $order->id,
                            'temple_id'        => $item['details']['temple_id'] ?? null,
                            'amount'           => $item['price'],
                            'status'           => 'Completed',
                            'transaction_id'   => $validated['razorpay_payment_id'],
                            'donation_purpose' => $item['details']['donation_purpose'] ?? 'General Donation',
                        ]);
                    }
                }

                $finalOrder = $order;
            });

            if ($finalOrder) {
                // This sends the confirmation to the customer
                Mail::to($user->email)->send(new OrderConfirmation($finalOrder));
            }

            Payment::create([
                'user_id'      => $user->id,
                'type'         => 'order',
                'reference_id' => null,
                'order_id'     => $validated['razorpay_order_id'],
                'payment_id'   => $validated['razorpay_payment_id'],
                'signature'    => $validated['razorpay_signature'],
                'amount'       => $totalAmount,
                'status'       => 'success',
                'payload'      => json_encode($validated),
            ]);

            session()->forget('cart');

            return redirect()->route('profile.my-orders.index')->with('success', 'Payment successful! Your order has been placed.');

        } catch (Exception $e) {
            Log::error('Payment failed for order ' . $request->razorpay_order_id . ': ' . $e->getMessage());
            return redirect()->route('cart.view')->with('error', $e->getMessage() ?: 'A problem occurred while processing your payment.');
        }
    }
    public function addDonation(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:10',
            'temple_id' => 'nullable|exists:temples,id',
            'donation_purpose' => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart', []);
        $cartItemId = 'donation_' . time();

        $name = 'General Donation';
        if ($validated['temple_id']) {
            $temple = Temple::find($validated['temple_id']);
            $name = 'Donation to ' . $temple->name;
        }

        $cart[$cartItemId] = [
            'id'       => $cartItemId,
            'type'     => 'donation',
            'name'     => $name,
            'price'    => $validated['amount'],
            'quantity' => 1,
            'details'  => $validated
        ];

        session()->put('cart', $cart);

        return redirect()->route('cart.view')->with('success', 'Donation has been added to your cart!');
    }
    public function payNowStay(Request $request)
{
    // 1. Validate the data just like in addStay()
    $validated = $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'check_in_date' => 'required|date|after_or_equal:today',
        'check_out_date' => 'required|date|after:check_in_date',
        'number_of_guests' => 'required|integer|min:1',
        'email' => 'required|email|max:255',
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
        return back()->with('error', 'Check-out date must be at least one day after check-in.')->withInput();
    }

    // 2. Calculate the correct discounted price
    $totalAmount = $nights * $room->discounted_price;

    // 3. Create a temporary "cart" item for this single transaction
    $cartItemId = 'stay_' . $validated['room_id'] . '_' . time();
    $directCheckoutItem = [
        $cartItemId => [
            'id'       => $cartItemId,
            'type'     => 'stay',
            'name'     => $room->type . ' Room at ' . $room->hotel->name,
            'price'    => $totalAmount,
            'quantity' => 1,
            'details'  => $validated,
            'room'     => $room->toArray(),
            'nights'   => $nights
        ]
    ];

    // 4. Store this item in a special session key to not interfere with the main cart
    session()->put('direct_checkout_cart', $directCheckoutItem);

    // 5. Initiate the Razorpay payment process (copied from checkout() method)
    $amountInPaise = $totalAmount * 100;
    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

    try {
        $order = $api->order->create([
            'receipt'         => 'order_' . time() . '_' . Str::random(5),
            'amount'          => $amountInPaise,
            'currency'        => 'INR',
            'payment_capture' => 1,
        ]);
    } catch (Exception $e) {
        Log::error('Razorpay Order Creation Failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Could not connect to payment gateway. Please try again.');
    }

    // 6. Redirect to the payment page
    return view('cart.razorpay', [
        'order'        => $order,
        'amount'       => $totalAmount,
        'razorpay_key' => config('services.razorpay.key')
    ]);
}
}
