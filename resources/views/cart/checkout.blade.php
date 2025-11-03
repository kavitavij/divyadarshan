@extends('layouts.app')

{{-- REMOVED: @push('styles') for Bootstrap CSS --}}

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Checkout</h1>

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

        {{-- Cart Summary Table --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr class="text-left text-gray-600 uppercase text-sm">
                        <th class="py-3 px-6">Name</th>
                        <th class="py-3 px-6">Type</th>
                        <th class="py-3 px-6">Price</th>
                        <th class="py-3 px-6">Quantity</th>
                        <th class="py-3 px-6 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @php $totalAmount = 0; @endphp
                    @forelse ($cart as $item)
                        @php
                            // Ensure price and quantity are valid numbers
                            $itemPrice = $item['price'] ?? 0;
                            $itemQuantity = $item['quantity'] ?? 1;
                            $itemTotal = $itemPrice * $itemQuantity;
                            $totalAmount += $itemTotal;
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium">{{ $item['name'] }}</td>
                            <td class="py-4 px-6">{{ ucfirst($item['type']) }}</td>
                            <td class="py-4 px-6">₹{{ number_format($itemPrice, 2) }}</td>
                            <td class="py-4 px-6 text-center">{{ $itemQuantity }}</td>
                            <td class="py-4 px-6 text-right">₹{{ number_format($itemTotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-gray-500">
                                Your cart is empty.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($cart) > 0)
                    <tfoot class="font-bold">
                        <tr class="text-gray-700">
                            <td colspan="4" class="py-4 px-6 text-right text-lg">Subtotal</td>
                            <td class="py-4 px-6 text-right text-lg">₹{{ number_format($totalAmount, 2) }}</td>
                        </tr>
                        <tr class="bg-gray-50 text-xl">
                            <td colspan="4" class="py-4 px-6 text-right">Grand Total</td>
                            <td class="py-4 px-6 text-right">₹{{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        {{-- Payment Options --}}
        @if (count($cart) > 0)
            {{-- UPDATED: Added Alpine.js x-data component --}}
            <div class="mt-8" x-data="{ showModal: false }">

                {{-- Check if any gateways are active --}}
                @if ($activeGateways->isEmpty())
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                        role="alert">
                        No payment methods are currently available. Please contact support.
                    </div>

                    {{-- CASE 1: Only ONE gateway is active. Show its button directly. --}}
                @elseif ($activeGateways->count() === 1)
                    <h2 class="text-2xl font-semibold mb-4">Proceed to Payment</h2>
                    <div class="w-full md:w-auto">
                        @if ($razorpay = $activeGateways->firstWhere('name', 'razorpay'))
                            <form action="{{ route('cart.pay') }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                <button type="submit"
                                    class="w-full md:w-auto px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-3">
                                    Proceed to Payment
                                </button>
                            </form>
                        @elseif ($activeGateways->firstWhere('name', 'stripe'))
                            @if ($totalAmount >= 42)
                                <form action="{{ route('cart.pay.stripe') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full md:w-auto px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-3">
                                        Proceed to Payment
                                    </button>
                                </form>
                            @else
                                <div>
                                    <button type="button"
                                        class="w-full md:w-auto px-8 py-3 bg-gray-400 text-white rounded-lg font-semibold text-lg flex items-center justify-center gap-3 cursor-not-allowed"
                                        disabled title="Stripe requires a minimum transaction of ₹42.00">
                                        Proceed to Payment
                                    </button>
                                    <p class="text-sm text-gray-500 text-center md:text-left mt-1">Stripe is unavailable for
                                        totals under ₹42.00</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- CASE 2: MULTIPLE gateways are active. Show a modal trigger button. --}}
                @else
                    <h2 class="text-2xl font-semibold mb-4">Proceed to Payment</h2>
                    {{-- UPDATED: Changed button to use Alpine's @click event --}}
                    <button type="button"
                        class="w-full md:w-auto px-8 py-3 bg-green-600 text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition-colors"
                        @click="showModal = true">
                        Proceed to Payment
                    </button>
                @endif

                {{-- NEW: Tailwind/Alpine Modal --}}
                @if (count($cart) > 0 && $activeGateways->count() > 1)
                    <!-- Payment Modal Backdrop -->
                    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity"
                        @click="showModal = false" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0" x-cloak>
                    </div>

                    <!-- Payment Modal Panel -->
                    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-auto" @click.away="showModal = false"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

                            {{-- Modal Header --}}
                            <div class="flex justify-between items-center p-4 border-b">
                                <h5 class="text-xl font-semibold">Select Payment Method</h5>
                                <button type="button" class="text-gray-400 hover:text-gray-600" @click="showModal = false">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Body --}}
                            <div class="p-6">
                                <div class="grid grid-cols-1 gap-4">

                                    {{-- RAZORPAY BUTTON --}}
                                    @if ($razorpay = $activeGateways->firstWhere('name', 'razorpay'))
                                        <form action="{{ route('cart.pay') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors flex items-center justify-center gap-3">
                                                <img src="https://razorpay.com/favicon.png" alt="Razorpay" class="w-6 h-6">
                                                Pay with Razorpay
                                            </button>
                                        </form>
                                    @endif

                                    {{-- STRIPE BUTTON --}}
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
                                            {{-- Disabled Stripe Button IN THE MODAL --}}
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
        @endif
    </div>
@endsection
