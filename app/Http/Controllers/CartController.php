<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seva;
use App\Models\Ebook;

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
        if(isset($cart[$index])) unset($cart[$index]);
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item removed!');
    }
    public function updateQuantity(Request $request, $index)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$index])) {
        $quantity = $request->input('quantity', 1);
        if($quantity < 1) $quantity = 1;
        $cart[$index]['quantity'] = $quantity;
    }

    session()->put('cart', $cart);
    return redirect()->back()->with('success', 'Cart updated!');
}
public function checkout()
{
    $cart = session()->get('cart', []);
    if(empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    $totalAmount = collect($cart)->sum(function($item){
        return $item['price'] * $item['quantity'];
    });

    return view('cart.checkout', compact('cart', 'totalAmount'));
}
public function pay()
{
    $cart = session()->get('cart', []);
    if(empty($cart)) {
        return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    }

    $totalAmount = collect($cart)->sum(function($item){
        return $item['price'] * $item['quantity'];
    });

    // Here integrate your payment gateway, and only after success:
    // session()->forget('cart');

    return redirect()->route('cart.index')->with('success', "Payment of ₹$totalAmount completed!");
}

}
