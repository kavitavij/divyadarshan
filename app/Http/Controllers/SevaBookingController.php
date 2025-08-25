<?php

namespace App\Http\Controllers;

use App\Models\Seva;
use App\Models\Temple;
use App\Models\SevaBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SevaBookingController extends Controller
{
    public function index(Request $request)
    {
        $temples = Temple::orderBy('name')->get();
        $selectedTemple = null;
        $sevas = [];

        if ($request->has('temple_id')) {
            $selectedTemple = Temple::find($request->input('temple_id'));
            if ($selectedTemple) {
                $sevas = $selectedTemple->sevas()->get();
            }
        }

        return view('sevas.booking', compact('temples', 'selectedTemple', 'sevas'));
    }

    /**
     * Add seva to cart (session) without redirecting.
     */
    public function addToCart(Request $request)
{
    // Validate input
    $request->validate([
        'seva_id' => 'required|exists:sevas,id',
    ]);

    // Get the Seva model
    $seva = Seva::findOrFail($request->input('seva_id'));

    // Get existing cart from session or empty array
    $cart = session()->get('cart', []);

    // Add/update cart item
    $cart[$seva->id]['quantity'] = ($cart[$seva->id]['quantity'] ?? 0) + 1;
    $cart[$seva->id]['name'] = $seva->name;
    $cart[$seva->id]['price'] = $seva->price;

    // Save back to session
    session()->put('cart', $cart);

    return response()->json([
        'success' => true,
        'message' => $seva->name . ' added to cart'
    ]);
}

    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('sevas.cart', compact('cart'));
    }
}
