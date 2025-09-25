@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 bg-white text-black dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

        @if (session('cart') && count(session('cart')) > 0)
            <div class="hidden md:block">
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Item Details</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Price</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-center">Quantity</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Total</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('cart') as $index => $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 align-middle">
                                    <div class="font-medium">{{ $item['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Type: {{ ucfirst($item['type']) }}</div>
                                    @if ($item['type'] === 'stay')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }} - 
                                            {{ \Carbon\Carbon::parse($item['details']['check_out_date'])->format('d M, Y') }} 
                                            ({{ $item['nights'] }} Nights)
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 align-middle">
                                    {{-- ======================================================= --}}
                                    {{-- START: DESKTOP PRICE LOGIC WITH DISCOUNTS           --}}
                                    {{-- ======================================================= --}}
                                    @if ($item['type'] === 'ebook' && isset($item['original_price']))
                                        <div>
                                            <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                            <span class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($item['original_price'], 2) }}</span>
                                        </div>
                                    @elseif ($item['type'] === 'stay' && $item['room']['discount_percentage'] > 0)
                                        <div>
                                            <span class="font-bold">₹{{ number_format($item['price'] / $item['nights'], 2) }}</span>
                                            <span class="text-xs text-gray-500">/ night</span>
                                        </div>
                                        <div class="text-xs text-gray-500 line-through">
                                            ₹{{ number_format($item['room']['price_per_night'], 2) }}
                                        </div>
                                    @else
                                        ₹{{ number_format($item['price'], 2) }}
                                        @if($item['type'] === 'stay') <span class="text-xs text-gray-500">/ night</span> @endif
                                    @endif
                                    {{-- ======================================================= --}}
                                    {{-- END: DESKTOP PRICE LOGIC                            --}}
                                    {{-- ======================================================= --}}
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center align-middle">
                                    @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                        <form action="{{ route('cart.updateQuantity', $index) }}" method="POST" class="inline-flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                            <button type="submit" class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">Update</button>
                                        </form>
                                    @else
                                        {{ $item['quantity'] }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 font-bold align-middle">
                                    @php
                                        $totalItemPrice = ($item['type'] === 'seva' || $item['type'] === 'ebook') ? $item['price'] * $item['quantity'] : $item['price'];
                                    @endphp
                                    ₹{{ number_format($totalItemPrice, 2) }}
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 align-middle">
                                    <form action="{{ route('cart.remove', $index) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="block md:hidden space-y-4">
                @foreach (session('cart') as $index => $item)
                    <div class="border rounded-lg shadow p-4 bg-white dark:bg-gray-800 dark:text-white border-gray-200 dark:border-gray-700">
                        <div class="font-semibold text-lg">{{ $item['name'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Type: {{ ucfirst($item['type']) }}</div>

                        {{-- ======================================================= --}}
                        {{-- START: MOBILE PRICE LOGIC WITH DISCOUNTS              --}}
                        {{-- ======================================================= --}}
                        <div class="mt-2 text-sm">
                            <span class="font-medium">Price: </span>
                            @if ($item['type'] === 'ebook' && isset($item['original_price']))
                                <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                <span class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($item['original_price'], 2) }}</span>
                            @elseif ($item['type'] === 'stay' && $item['room']['discount_percentage'] > 0)
                                @php
                                    $originalTotal = $item['nights'] * $item['room']['price_per_night'];
                                @endphp
                                <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                <span class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($originalTotal, 2) }}</span>
                                <span class="text-xs text-gray-500">for {{ $item['nights'] }} nights</span>
                            @else
                                <span class="font-medium">₹{{ number_format($item['price'], 2) }}</span>
                            @endif
                        </div>
                        {{-- ======================================================= --}}
                        {{-- END: MOBILE PRICE LOGIC                               --}}
                        {{-- ======================================================= --}}

                        <div class="mt-1 text-sm">
                            Quantity:
                            @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                <form action="{{ route('cart.updateQuantity', $index) }}" method="POST" class="flex items-center gap-2 mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 dark:hover:bg-blue-400 text-white rounded text-xs">Update</button>
                                </form>
                            @else
                                {{ $item['quantity'] }}
                            @endif
                        </div>

                        <div class="mt-2 font-semibold">
                            Total: ₹{{ number_format(($item['type'] === 'seva' || $item['type'] === 'ebook') ? $item['price'] * $item['quantity'] : $item['price'], 2) }}
                        </div>

                        <form action="{{ route('cart.remove', $index) }}" method="POST" class="mt-3">
                            @csrf
                            @method('DELETE')
                            <button class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>

            @php
                $totalAmount = collect(session('cart'))->sum(function ($item) {
                    return ($item['type'] === 'seva' || $item['type'] === 'ebook')
                        ? $item['price'] * $item['quantity']
                        : $item['price'];
                });
            @endphp

            <div class="mt-6 flex flex-col md:flex-row justify-between items-center">
                <div class="text-2xl font-bold mb-4 md:mb-0">Total: ₹{{ number_format($totalAmount, 2) }}</div>
                <form action="{{ route('cart.checkout') }}" method="GET">
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 dark:hover:bg-green-500 text-white rounded-lg font-semibold w-full md:w-auto">
                        Proceed to Pay
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">Your cart is empty.</p>
        @endif
    </div>
@endsection