@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order #{{ $order->order_number }}</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Placed on {{ $order->created_at->format('F jS, Y') }}</p>
                </div>
                <a href="{{ route('profile.my-orders.download-invoice', $order) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                    Download Invoice
                </a>
            </div>
        </div>

        <!-- Order Items -->
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach($order->order_details as $item)
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Qty: {{ $item['quantity'] }}</p>
                    </div>
                    <p class="font-semibold text-gray-800 dark:text-gray-200">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                </div>
            @endforeach
        </div>

        <!-- Order Total -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 rounded-b-lg">
            <div class="flex justify-end items-center gap-4">
                <span class="text-lg font-medium text-gray-800 dark:text-gray-200">Total:</span>
                <span class="text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
