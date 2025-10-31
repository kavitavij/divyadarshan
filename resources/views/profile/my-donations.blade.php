@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Donation History</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A record of all your generous contributions. Thank you for your support.</p>
        </div>

        <div class="flow-root">
            @if($donations->isEmpty())
                <div class="text-center py-12 px-6">
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No donations yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You have not made any donations. Your support is greatly appreciated.</p>
                    <div class="mt-6">
                        <a href="{{ route('donations.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            Make a Donation
                        </a>
                    </div>
                </div>
            @else
                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($donations as $donation)
                        <li class="px-6 py-5">
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400 truncate">
                                        Order #{{ $donation->order->order_number ?? 'N/A' }}
                                    </p>
                                    <div class="mt-2 space-y-2 sm:flex sm:items-center sm:gap-4 sm:space-y-0">
                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zM4.5 8.5v6.75c0 .138.112.25.25.25h10.5a.25.25 0 00.25-.25V8.5h-11z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $donation->created_at->format('F jS, Y') }}
                                        </p>
                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400 capitalize">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                              <path d="M3.25 4A2.25 2.25 0 001 6.25v7.5A2.25 2.25 0 003.25 16h13.5A2.25 2.25 0 0019 13.75v-7.5A2.25 2.25 0 0016.75 4H3.25zM16.5 6.25a.75.75 0 00-.75-.75H4a.75.75 0 00-.75.75v7.5c0 .414.336.75.75.75h12a.75.75 0 00.75-.75v-7.5zM9 12a1 1 0 112 0 1 1 0 01-2 0zM6.5 10.5a1 1 0 100-2 1 1 0 000 2zM12.5 10.5a1 1 0 100-2 1 1 0 000 2z" />
                                            </svg>
                                            For: {{ $donation->temple->name ?? 'General Donation' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex sm:flex-col sm:items-end sm:text-right gap-4">
                                    <div class="flex-1">
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">₹{{ number_format($donation->amount, 2) }}</p>
                                        <span class="mt-1 inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-200">{{ $donation->status }}</span>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('donations.receipt.download', $donation) }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            Download Receipt
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
