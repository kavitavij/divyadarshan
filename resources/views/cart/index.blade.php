@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

        @if (session('cart') && count(session('cart')) > 0)
            <table class="min-w-full bg-white border">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Item Details</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Type</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Price</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Quantity</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Total</th>
                        <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('cart') as $index => $item)
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 border-b">
                                <div class="font-medium">{{ $item['name'] }}</div>
                                @if ($item['type'] === 'darshan')
                                    <div class="text-xs text-gray-500">Date:
                                        {{ \Carbon\Carbon::parse($item['details']['selected_date'])->format('d M, Y') }} |
                                        Devotees: {{ $item['details']['number_of_people'] }}</div>
                                @elseif ($item['type'] === 'stay')
                                    <div class="text-xs text-gray-500">Check-in:
                                        {{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }} |
                                        Nights: {{ $item['nights'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 border-b">{{ ucfirst($item['type']) }}</td>
                            <td class="px-6 py-4 border-b">₹{{ number_format($item['price'], 2) }}</td>
                            <td class="px-6 py-4 border-b">
                                @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                    <form action="{{ route('cart.updateQuantity', $index) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            class="w-16 text-center border rounded">
                                        <button type="submit"
                                            class="px-3 py-1 bg-blue-500 text-white rounded text-xs">Update</button>
                                    </form>
                                @else
                                    {{ $item['quantity'] }}
                                @endif
                            </td>
                            <td class="px-6 py-4 border-b">
                                @php
                                    $totalItemPrice =
                                        $item['type'] === 'seva' || $item['type'] === 'ebook'
                                            ? $item['price'] * $item['quantity']
                                            : $item['price'];
                                @endphp
                                ₹{{ number_format($totalItemPrice, 2) }}
                            </td>
                            <td class="px-6 py-4 border-b">
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

            @php
                $totalAmount = collect(session('cart'))->sum(function ($item) {
                    if ($item['type'] === 'seva' || $item['type'] === 'ebook') {
                        return $item['price'] * $item['quantity'];
                    }
                    return $item['price']; // For darshan and stays, price is already the total
                });
            @endphp

            <div class="mt-6 flex justify-between items-center">
                <div class="text-2xl font-bold">Total: ₹{{ number_format($totalAmount, 2) }}</div>
                <form action="{{ route('cart.checkout') }}" method="GET">
                    <button type="submit"
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">Proceed to
                        Checkout</button>
                </form>
            </div>
        @else
            <p class="text-gray-500">Your cart is empty.</p>
        @endif
    </div>
@endsection
