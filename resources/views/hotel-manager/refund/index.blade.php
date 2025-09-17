@extends('layouts.hotel-manager')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- CORRECTED THE TITLE --}}
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Refund & Cancellation Requests</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        {{-- CORRECTED TABLE HEADERS --}}
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Booking ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Guest Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Dates
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- CORRECTED LOOP VARIABLE TO $refundRequests --}}
                    @forelse ($refundRequests as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <p class="text-gray-900 dark:text-white whitespace-no-wrap">#{{ $booking->id }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <p class="text-gray-900 dark:text-white whitespace-no-wrap">{{ $booking->user->name }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <p class="text-gray-900 dark:text-white whitespace-no-wrap">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <p class="text-gray-900 dark:text-white whitespace-no-wrap">₹{{ number_format($booking->total_amount, 2) }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                                    <span aria-hidden class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                    <span class="relative">{{ ucfirst($booking->refund_status) }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                                <a href="{{ route('hotel-manager.refund.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                    View Details
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-5 text-center text-gray-500">
                                No pending refund requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-5 bg-white dark:bg-gray-800 border-t flex flex-col xs:flex-row items-center xs:justify-between">
            {{ $refundRequests->links() }}
        </div>
    </div>
</div>
@endsection
