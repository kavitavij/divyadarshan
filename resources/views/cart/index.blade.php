@extends('layouts.app')

{{-- Responsive table styles --}}
@push('styles')
    {{-- This is used for the mobile card view --}}
    <style>
        @media (max-width: 767.98px) {
            .table-responsive-stack thead {
                display: none;
            }

            .table-responsive-stack tbody,
            .table-responsive-stack tr,
            .table-responsive-stack td {
                display: block;
                width: 100%;
            }

            .table-responsive-stack tr {
                margin-bottom: 1rem;
                border: 1px solid #e9ecef;
                border-radius: 0.5rem;
                overflow: hidden;
            }

            .table-responsive-stack td {
                padding: 0.75rem;
                border: none;
                border-bottom: 1px solid #e9ecef;
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: right;
            }

            .table-responsive-stack tr:first-child td:first-child {
                border-top-left-radius: 0.5rem;
                border-top-right-radius: 0.5rem;
            }

            .table-responsive-stack tr:last-child td:last-child {
                border-bottom-left-radius: 0.5rem;
                border-bottom-right-radius: 0.5rem;
                border-bottom: 0;
            }

            .table-responsive-stack td:last-child {
                border-bottom: 0;
            }

            .table-responsive-stack td::before {
                content: attr(data-label);
                font-weight: 600;
                width: 40%;
                text-align: left;
                padding-right: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div
        class="container mx-auto px-4 py-8 bg-white text-black dark:bg-gray-900 dark:text-white transition-colors duration-300">

        {{-- Session Alert Block --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        {{-- END NEW BLOCK --}}

        @if (session('cart') && count(session('cart')) > 0)
            @php
                // Calculate total amount once
                $totalAmount = collect(session('cart'))->sum(function ($item) {
                    $itemPrice = $item['price'] ?? 0;
                    $itemQuantity = $item['quantity'] ?? 1;
                    return $itemPrice * $itemQuantity;
                });
            @endphp

            <h1 class="text-3xl font-bold mb-6">Your Cart</h1>

            {{-- UPDATED: Two-column layout --}}
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 lg:items-start" x-data="{ showModal: false }">

                {{-- LEFT COLUMN: Cart Items --}}
                <section class="lg:col-span-8">

                    {{-- Desktop Cart Table --}}
                    <div
                        class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <table class="min-w-full bg-white dark:bg-gray-800">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Item
                                        Details</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-left">Price
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-center">
                                        Quantity</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-right">Total
                                    </th>
                                    <th class="px-6 py-3 border-b-2 border-gray-300 dark:border-gray-600 text-center">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-300">
                                @foreach (session('cart') as $index => $item)
                                    @php
                                        $itemPrice = $item['price'] ?? 0;
                                        $itemQuantity = $item['quantity'] ?? 1;
                                        $itemTotal = $itemPrice * $itemQuantity;
                                    @endphp
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 align-middle">
                                            <div class="font-medium">{{ $item['name'] }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Type:
                                                {{ ucfirst($item['type']) }}</div>
                                            @if ($item['type'] === 'stay')
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($item['details']['check_in_date'])->format('d M, Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($item['details']['check_out_date'])->format('d M, Y') }}
                                                    ({{ $item['nights'] }} Nights)
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 align-middle">
                                            @if ($item['type'] === 'ebook' && isset($item['original_price']))
                                                <div>
                                                    <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                                    <span
                                                        class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($item['original_price'], 2) }}</span>
                                                </div>
                                            @elseif ($item['type'] === 'stay' && $item['room']['discount_percentage'] > 0)
                                                <div>
                                                    <span
                                                        class="font-bold">₹{{ number_format($item['price'] / $item['nights'], 2) }}</span>
                                                    <span class="text-xs text-gray-500">/ night</span>
                                                </div>
                                                <div class="text-xs text-gray-500 line-through">
                                                    ₹{{ number_format($item['room']['price_per_night'], 2) }}
                                                </div>
                                            @else
                                                ₹{{ number_format($item['price'], 2) }}
                                                @if ($item['type'] === 'stay')
                                                    <span class="text-xs text-gray-500">/ night</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center align-middle">
                                            @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                                <form action="{{ route('cart.updateQuantity', $index) }}" method="POST"
                                                    class="inline-flex items-center">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                        min="1"
                                                        class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                                    <button type="submit"
                                                        class="ml-2 px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs">Update</button>
                                                </form>
                                            @else
                                                {{ $item['quantity'] }}
                                            @endif
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 font-bold align-middle text-right">
                                            ₹{{ number_format($itemTotal, 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 text-center align-middle">
                                            <form action="{{ route('cart.remove', $index) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Cart View (using responsive table styles) --}}
                    <div class="block md:hidden space-y-4">
                        @foreach (session('cart') as $index => $item)
                            @php
                                $itemPrice = $item['price'] ?? 0;
                                $itemQuantity = $item['quantity'] ?? 1;
                                $itemTotal = $itemPrice * $itemQuantity;
                            @endphp
                            <div
                                class="border rounded-lg shadow p-4 bg-white dark:bg-gray-800 dark:text-white border-gray-200 dark:border-gray-700">
                                <div class="font-semibold text-lg">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Type:
                                    {{ ucfirst($item['type']) }}</div>

                                <div class="mt-2 text-sm">
                                    <span class="font-medium">Price: </span>
                                    @if ($item['type'] === 'ebook' && isset($item['original_price']))
                                        <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                        <span
                                            class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($item['original_price'], 2) }}</span>
                                    @elseif ($item['type'] === 'stay' && $item['room']['discount_percentage'] > 0)
                                        @php
                                            $originalTotal = $item['nights'] * $item['room']['price_per_night'];
                                        @endphp
                                        <span class="font-bold">₹{{ number_format($item['price'], 2) }}</span>
                                        <span
                                            class="text-xs text-gray-500 line-through ml-1">₹{{ number_format($originalTotal, 2) }}</span>
                                        <span class="text-xs text-gray-500">for {{ $item['nights'] }} nights</span>
                                    @else
                                        <span class="font-medium">₹{{ number_format($item['price'], 2) }}</span>
                                    @endif
                                </div>

                                <div class="mt-1 text-sm">
                                    <span class="font-medium">Quantity:</span>
                                    @if ($item['type'] === 'seva' || $item['type'] === 'ebook')
                                        <form action="{{ route('cart.updateQuantity', $index) }}" method="POST"
                                            class="flex items-center gap-2 mt-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                                min="1"
                                                class="w-16 text-center border rounded bg-white dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                            <button type="submit"
                                                class="px-3 py-1 bg-blue-500 hover:bg-blue-600 dark:hover:bg-blue-400 text-white rounded text-xs">Update</button>
                                        </form>
                                    @else
                                        <span class="ml-1">{{ $item['quantity'] }}</span>
                                    @endif
                                </div>

                                <div class="mt-2 font-semibold">
                                    Total: ₹{{ number_format($itemTotal, 2) }}
                                </div>

                                <form action="{{ route('cart.remove', $index) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded">Remove</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- RIGHT COLUMN: Order Summary (Sticky) --}}
                <section class="lg:col-span-4 space-y-6">
                    <div
                        class="sticky top-24 bg-white dark:bg-gray-800 shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="p-6">
                            <h2 class="text-2xl font-semibold mb-4">Order Summary</h2>
                            <div class="flex justify-between items-center text-gray-700 dark:text-gray-300 mb-2">
                                <span>Subtotal</span>
                                <span class="font-medium">₹{{ number_format($totalAmount, 2) }}</span>
                            </div>

                            {{-- Add other lines like "Taxes" or "Fees" here if needed --}}

                            <hr class="my-4 border-gray-200 dark:border-gray-600">

                            <div class="flex justify-between items-center text-xl font-bold text-gray-900 dark:text-white">
                                <span>Grand Total</span>
                                <span>₹{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>

                        {{-- Payment Button Logic --}}
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            @if (!isset($activeGateways))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Error:</strong>
                                    <span class="block sm:inline">Gateways not configured.</span>
                                </div>
                            @elseif ($activeGateways->isEmpty())
                                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                                    role="alert">
                                    No payment methods are available.
                                </div>

                                {{-- CASE 1: Only ONE gateway active --}}
                            @elseif ($activeGateways->count() === 1)
                                @if ($razorpay = $activeGateways->firstWhere('name', 'razorpay'))
                                    <form action="{{ route('cart.pay') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors">
                                            Proceed to Payment
                                        </button>
                                    </form>
                                @elseif ($stripe = $activeGateways->firstWhere('name', 'stripe'))
                                    @if ($totalAmount >= 42)
                                        <form action="{{ route('cart.pay.stripe') }}" method="POST" class="w-full">
                                            @csrf
                                            <button type="submit"
                                                class="w-full px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors">
                                                Proceed to Payment
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                            class="w-full px-8 py-3 bg-gray-400 text-white rounded-lg font-semibold text-lg cursor-not-allowed"
                                            disabled>
                                            Proceed to Payment
                                        </button>
                                        <p class="text-sm text-gray-500 text-center mt-2">Stripe is unavailable for totals
                                            under ₹42.00</p>
                                    @endif
                                @endif

                                {{-- CASE 2: MULTIPLE gateways active --}}
                            @else
                                <button type="button"
                                    class="w-full px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors"
                                    @click="showModal = true">
                                    Proceed to Payment
                                </button>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Tailwind/Alpine Modal (Placed outside the grid) --}}
                @if (isset($activeGateways) && $activeGateways->count() > 1)
                    <!-- Payment Modal Backdrop -->
                    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
                        @click="showModal = false" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" x-cloak>
                    </div>

                    <!-- Payment Modal Panel -->
                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto dark:bg-gray-800"
                            @click.away="showModal = false" x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90">

                            <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                                <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Select Payment Method</h5>
                                <button type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                    @click="showModal = false">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-4">

                                    @if ($razorpay = $activeGateways->firstWhere('name', 'razorpay'))
                                        <form action="{{ route('cart.pay') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-3">
                                                <img src="https://razorpay.com/favicon.png" alt="Razorpay"
                                                    class="w-6 h-6">
                                                Pay with Razorpay
                                            </button>
                                        </form>
                                    @endif

                                    @if ($stripe = $activeGateways->firstWhere('name', 'stripe'))
                                        @if ($totalAmount >= 42)
                                            <form action="{{ route('cart.pay.stripe') }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg font-semibold text-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-3">
                                                    <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 448 512">
                                                        <path
                                                            d="M439.4 153.3c-2.4-1.6-4-4.2-4.9-7.1 -3.4-11.4-13.8-19.1-26.6-19.1H32V384h112c30.9 0 56-25.1 56-56s-25.1-56-56-56H80v-64h299.9c8.5 0 16.2 4.1 21.1 10.7 4.9 6.6 6.8 15 4.9 23.1 -1.9 8.1-8.3 14.1-16.7 15.1l-102.7 11.5c-4.9 .5-9.1 4.1-9.8 9L270 317.1c-.8 5.6 2.3 11 7.7 12.6 5.4 1.6 11-2.3 12.6-7.7l17.8-63.7c2.1-7.6 8.9-12.9 16.9-12.9h54.9c30.9 0 56 25.1 56 56s-25.1 56-56 56H216v64h203.8c14.2 0 26.6-9.5 30-22.7 3.4-13.2-3.2-26.7-14.4-33.3z" />
                                                    </svg>
                                                    Pay with Stripe
                                                </button>
                                            </form>
                                        @else
                                            <div>
                                                <button type="button"
                                                    class="w-full px-6 py-3 bg-gray-400 text-white rounded-lg font-semibold text-lg flex items-center justify-center gap-3 cursor-not-allowed"
                                                    disabled title="Stripe requires a minimum transaction of ₹42.00">
                                                    <svg class="w-6 h-6 fill-current" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 448 512">
                                                        <path
                                                            d="M439.4 153.3c-2.4-1.6-4-4.2-4.9-7.1 -3.4-11.4-13.8-19.1-26.6-19.1H32V384h112c30.9 0 56-25.1 56-56s-25.1-56-56-56H80v-64h299.9c8.5 0 16.2 4.1 21.1 10.7 4.9 6.6 6.8 15 4.9 23.1 -1.9 8.1-8.3 14.1-16.7 15.1l-102.7 11.5c-4.9 .5-9.1 4.1-9.8 9L270 317.1c-.8 5.6 2.3 11 7.7 12.6 5.4 1.6 11-2.3 12.6-7.7l17.8-63.7c2.1-7.6 8.9-12.9 16.9-12.9h54.9c30.9 0 56 25.1 56 56s-25.1 56-56 56H216v64h203.8c14.2 0 26.6-9.5 30-22.7 3.4-13.2-3.2-26.7-14.4-33.3z" />
                                                    </svg>
                                                    Pay with Stripe
                                                </button>
                                                <p class="text-sm text-gray-500 text-center mt-1">Stripe is unavailable for
                                                    totals under ₹42.00</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="mt-4 text-2xl font-semibold text-gray-900 dark:text-white">Your cart is empty</h2>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Looks like you haven't added anything to your cart yet.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors">
                    Continue Browsing
                </a>
            </div>
        </div>
        @endif
    </div>
@endsection
