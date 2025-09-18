@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 bg-white text-black dark:bg-gray-900 dark:text-white transition-colors duration-300">
        <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

        @if (session('cart') && count(session('cart')) > 0)
            <!-- Desktop Table View -->
            <div class="hidden md:block">
                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Item Details</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Type</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Price</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Quantity</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Total</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('cart') as $index => $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="font-medium">{{ $item['name'] }}</div>
                                    @if ($item['type'] === 'darshan')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Date:
                                            {{ \Carbon\Carbon::parse($item['details']['selected_date'])->format('d M, Y') }}
                                            |
                                            Devotees: {{ $item['details']['number_of_people'] }}
                                        </div>
                                    @elseif ($item['type'] === 'stay')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Check-in:
                                            {{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }}
                                            |
                                            Nights: {{ $item['nights'] }}
                                        </div>
                                    @elseif ($item['type'] === 'donation' && !empty($item['details']['donation_purpose']))
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Purpose: {{ Illuminate\Support\Str::ucfirst(str_replace('_', ' ', $item['details']['donation_purpose'])) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">{{ ucfirst($item['type']) }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">₹{{ number_format($item['price'], 2) }}</td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                        <form action="{{ route('cart.updateQuantity', $index) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                                class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                            <button type="submit"
                                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 dark:hover:bg-blue-400 text-white rounded text-xs mt-1">Update</button>
                                        </form>
                                    @else
                                        {{ $item['quantity'] }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    @php
                                        $totalItemPrice = ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                            ? $item['price'] * $item['quantity']
                                            : $item['price'];
                                    @endphp
                                    ₹{{ number_format($totalItemPrice, 2) }}
                                </td>
                                <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                    <form action="{{ route('cart.remove', $index) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="block md:hidden space-y-4">
                @foreach (session('cart') as $index => $item)
                    @php
                        $totalItemPrice = ($item['type'] === 'seva' || $item['type'] === 'ebook')
                            ? $item['price'] * $item['quantity']
                            : $item['price'];
                    @endphp
                    <div class="border rounded-lg shadow p-4 bg-white dark:bg-gray-800 dark:text-white border-gray-200 dark:border-gray-700">
                        <div class="font-semibold text-lg">{{ $item['name'] }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Type: {{ ucfirst($item['type']) }}</div>

                        @if ($item['type'] === 'darshan')
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Date: {{ \Carbon\Carbon::parse($item['details']['selected_date'])->format('d M, Y') }} |
                                Devotees: {{ $item['details']['number_of_people'] }}
                            </div>
                        @elseif ($item['type'] === 'stay')
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Check-in: {{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }}
                                |
                                Nights: {{ $item['nights'] }}
                            </div>
                        @elseif ($item['type'] === 'donation' && !empty($item['details']['donation_purpose']))
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Purpose: {{ Illuminate\Support\Str::ucfirst(str_replace('_', ' ', $item['details']['donation_purpose'])) }}
                            </div>
                        @endif

                        <div class="mt-2 text-sm">Price: <span class="font-medium">₹{{ number_format($item['price'], 2) }}</span></div>

                        <div class="mt-1 text-sm">
                            Quantity:
                            @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                <form action="{{ route('cart.updateQuantity', $index) }}" method="POST"
                                    class="flex items-center gap-2 mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                    <button type="submit"
                                        class="px-3 py-1 bg-blue-500 hover:bg-blue-600 dark:hover:bg-blue-400 text-white rounded text-xs">Update</button>
                                </form>
                            @else
                                {{ $item['quantity'] }}
                            @endif
                        </div>

                        <div class="mt-2 font-semibold">Total: ₹{{ number_format($totalItemPrice, 2) }}</div>

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
                    <button type="submit"
                        class="px-6 py-3 bg-green-600 hover:bg-green-700 dark:hover:bg-green-500 text-white rounded-lg font-semibold w-full md:w-auto">
                        Proceed to Pay
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400">Your cart is empty.</p>
        @endif
    </div>
@endsection
