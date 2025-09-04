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
use App\Models\Donation;
use App\Models\Devotee;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Str;
use App\Models\DefaultDarshanSlot;
use App\Models\DarshanSlot;

class CartController extends Controller
{
    public function addSeva(Request $request)
    {
        $seva = Seva::findOrFail($request->seva_id);
        $cart = session()->get('cart', []);
        $cart['seva_' . $seva->id] = [ // Use a unique key
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
        $cart['ebook_' . $ebook->id] = [ // Use a unique key
            'id' => $ebook->id,
            'type' => 'ebook',
            'name' => $ebook->title,
            'price' => $ebook->price,
            'quantity' => 1
        ];
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
    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.view')->with('error', 'Your cart is empty.');
        }

        $totalAmount = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });

        return view('cart.checkout', compact('cart', 'totalAmount'));
    }
    public function addDarshan(Request $request)
{
    // 1. UPDATED VALIDATION LOGIC
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
        'devotees.*.id_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048', // File validation
        'darshan_slot_id' => [
            'required',
            'string',
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
            $devotee['id_photo_path'] = $path; // Add the path to the array
            unset($devotee['id_photo']); // Remove the file object
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
        'quantity' => 1, // Darshan is a single package
        'details'  => $validatedData // This is now safe to store in the session
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

        $totalAmount = $nights * $room->price_per_night;
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

    /**
     * UPDATED: This is the fully corrected paymentSuccess method.
     */
    // In app/HttpControllers/CartController.php

    public function paymentSuccess(Request $request)
    {
        $validated = $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        $user = Auth::user();

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

                        // --- THE FIX IS HERE ---
                        // STEP 1: Create the main Booking record first
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
                            // Add any other required fields for the bookings table
                        ]);

                        // STEP 2: Now that we have a booking, save the devotees with its ID
                        foreach ($details['devotees'] as $devoteeData) {
                            Devotee::create([
                                'booking_id'   => $booking->id, // Use the ID from the booking we just created
                                'full_name'    => $devoteeData['full_name'],
                                'age'          => $devoteeData['age'],
                                'gender'       => $devoteeData['gender'],
                                'email'        => $devoteeData['email'],
                                'phone_number' => $devoteeData['phone_number'],
                                'pincode'      => $devoteeData['pincode'],
                                'city'         => $devoteeData['city'],
                                'state'        => $devoteeData['state'],
                                'address'      => $devoteeData['address'],
                                'id_type'      => $devoteeData['id_type'],
                                'id_number'    => $devoteeData['id_number'],
                                'id_photo_path' => $devoteeData['id_photo_path'] ?? null,
                            ]);
                        }
                    }
                    // Handle other booking types like stay, seva, etc.
                }

                $finalOrder = $order;
            });

            if ($finalOrder) {
                $user->notify(new \App\Notifications\OrderConfirmation($finalOrder));
            }

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
}
