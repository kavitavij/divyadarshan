@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

        <table class="min-w-full bg-white border mb-6">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ ucfirst($item['type']) }}</td>
                        <td>₹{{ number_format($item['price'], 2) }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (count($cart) > 0)
            <div class="mt-4">
                <a href="{{ route('cart.checkout') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Proceed to Checkout
                </a>
            </div>
        @endif

    </div>
@endsection
