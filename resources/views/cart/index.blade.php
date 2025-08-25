@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

        @if (count($cart) > 0)
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Quantity</th>

                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($cart as $index => $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ ucfirst($item['type']) }}</td>
                            <td>₹{{ number_format($item['price'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.updateQuantity', $index) }}" method="POST"
                                    class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                        onclick="let input=this.nextElementSibling; if(input.value>1){input.stepDown();}"
                                        class="px-2 py-1 bg-gray-300 rounded">-</button>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-16 text-center border rounded">
                                    <button type="submit" onclick="this.previousElementSibling.stepUp();"
                                        class="px-2 py-1 bg-gray-300 rounded">+</button>
                                </form>
                            </td>
                            <td>₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <form action="{{ route('cart.remove', $index) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-2 py-1 bg-red-500 text-white rounded">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (count($cart) > 0)
                @php
                    $totalAmount = collect($cart)->sum(function ($item) {
                        return $item['price'] * $item['quantity'];
                    });
                @endphp

                <div class="mt-4 flex justify-between items-center">
                    <div class="text-xl font-bold">
                        Total: ₹{{ number_format($totalAmount, 2) }}
                    </div>

                    <form action="{{ route('cart.checkout') }}" method="GET">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Proceed to Payment
                        </button>
                    </form>
                </div>
            @endif
        @else
            <p class="text-gray-500">Your cart is empty.</p>
        @endif
    </div>
@endsection
