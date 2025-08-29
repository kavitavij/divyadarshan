<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);

        return view('profile.my-orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View
    {
        // Security check: ensure the user owns the order
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        return view('profile.my-orders.show', compact('order'));
    }

    /**
     * Generate and download a PDF invoice for the specified order.
     */
    public function downloadInvoice(Order $order)
    {
        // Security check
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }

        $pdf = PDF::loadView('profile.my-orders.invoice', compact('order'));
        $fileName = 'Invoice-' . $order->order_number . '.pdf';

        return $pdf->stream($fileName);
    }
}

