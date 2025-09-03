@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">

        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Order History</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                A record of all your past transactions.
            </p>
        </div>

        <!-- Quick Navigation Tabs -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('profile.my-bookings.index') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm
                          bg-blue-600 text-white hover:bg-blue-700 transition">
                    View Darshan Bookings
                </a>
                <a href="{{ route('profile.my-stays.index') }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm
                        bg-red-600 text-white hover:bg-red-700 transition">
                    View Accommodation Bookings
                </a>
                <a href="{{ route('profile.my-donations.index') }}"
                   class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm
                          bg-green-600 text-white hover:bg-green-700 transition">
                    View Donations
                </a>

            </div>
        </div>

        <!-- Orders List -->
        <div class="flow-root">
            @if($orders->isEmpty())
                <div class="text-center py-12 px-6">
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">
                        No orders yet
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        You have not placed any orders yet.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md
                                  shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                            Explore Services
                        </a>
                    </div>
                </div>
            @else
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($orders as $order)
                        <li class="px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <div class="flex flex-col sm:flex-row justify-between gap-4">
                                <!-- Order Info -->
                                <div>
                                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">
                                        Order #{{ $order->order_number }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Placed on {{ $order->created_at->format('F jS, Y') }}
                                    </p>
                                </div>

                                <!-- Order Amount & Link -->
                                <div class="sm:text-right">
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        ₹{{ number_format($order->total_amount, 2) }}
                                    </p>
                                    <a href="{{ route('profile.my-orders.show', $order) }}"
                                       class="text-sm font-medium text-blue-600 hover:underline">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
