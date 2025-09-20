@extends('layouts.app')

@section('content')
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-extrabold mb-8 text-gray-800 dark:text-gray-200 text-center">
            My Accommodation Bookings
        </h1>
        <div class="mb-6">
            <!-- <form method="GET" action="{{ route('profile.my-stays.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <select name="status" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
                    <option value="">All Statuses</option>
                    <option value="Confirmed" {{ request('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <input
                    type="search"
                    name="q"
                    placeholder="Search by hotel name..."
                    value="{{ request('q') }}"
                    class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 w-full sm:w-64"/>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                    Search
                </button>

                <a href="{{ route('profile.my-stays.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Reset
                </a>
            </form>
        </div> -->
        <form method="GET" action="{{ route('profile.my-stays.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-wrap">
        {{-- Status filter --}}
        <select name="status" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            <option value="">All Statuses</option>
            <option value="Confirmed" {{ request('status') === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        {{-- Date range filter --}}
        <select name="date_range" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            <option value="">Any Time</option>
            <option value="1" {{ request('date_range') === '1' ? 'selected' : '' }}>Last 1 Month</option>
            <option value="3" {{ request('date_range') === '3' ? 'selected' : '' }}>Last 3 Months</option>
            <option value="6" {{ request('date_range') === '6' ? 'selected' : '' }}>Last 6 Months</option>
            <option value="this_year" {{ request('date_range') === 'this_year' ? 'selected' : '' }}>This Year</option>
        </select>

        {{-- Specific year filter --}}
        <select name="year" class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
            <option value="">All Years</option>
            @foreach (range(now()->year, now()->year - 5) as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>

        {{-- Hotel name search --}}
        <input
            type="search"
            name="q"
            placeholder="Search by hotel name..."
            value="{{ request('q') }}"
            class="px-3 py-2 rounded-lg border bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 w-full sm:w-64"/>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
            Search
        </button>

        <a href="{{ route('profile.my-stays.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
            Reset
        </a>
    </form>

        @if ($bookings->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0v-4m6 4v-4m6 4v-4" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-200">No Stay Bookings Found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You have not booked any accommodations yet.</p>
                <div class="mt-6">
                    <a href="{{ route('stays.index') }}" class="inline-flex items-center px-5 py-2 rounded-lg shadow bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                        🏨 Find a Place to Stay
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($bookings as $booking)
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
                    <div class="flex-grow">
                        {{-- Header with Hotel Name and Status --}}
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                🏨 {{ $booking->room->hotel->name }}
                            </h2>
                            @php
                                $statusText = $booking->status;
                                $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'; // Default

                                if (strtolower($booking->status) === 'confirmed') {
                                    if ($booking->payment_method === 'pay_at_hotel') {
                                        $statusText = 'Confirmed (Pay at Hotel)';
                                        // Use a different color to make it stand out
                                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                    } else {
                                        $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                    }
                                } elseif (strtolower($booking->status) === 'cancelled') {
                                    if ($booking->refund_status === 'Pending') {
                                        $statusText = 'Refund Pending';
                                        $statusClass = 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300';
                                    } elseif ($booking->refund_status === 'Successful') {
                                        $statusText = 'Refund Successful';
                                        $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                    } else {
                                        $statusText = 'Cancelled';
                                        $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                    }
                                }
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        {{-- Booking Details section --}}
                        <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <p><span class="font-semibold text-gray-500 dark:text-gray-400">Room:</span> {{ $booking->room->type }}</p>
                        <p><span class="font-semibold text-gray-500 dark:text-gray-400">Check-in:</span> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('F j, Y') }}</p>
                        <p><span class="font-semibold text-gray-500 dark:text-gray-400">Check-out:</span> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('F j, Y') }}</p>
                        <p><span class="font-semibold text-gray-500 dark:text-gray-400">Guests:</span> {{ $booking->number_of_guests }}</p>

                        {{-- Conditional Payment Info --}}
                        @if ($booking->payment_method == 'pay_at_hotel')
                            <p><span class="font-semibold text-gray-500 dark:text-gray-400">Amount Due at Hotel:</span> ₹{{ number_format($booking->total_amount, 2) }}</p>
                        @else
                            <p><span class="font-semibold text-gray-500 dark:text-gray-400">Amount Paid:</span> ₹{{ number_format($booking->total_amount, 2) }}</p>
                        @endif
                    </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        {{-- Left Side: Cancel Button --}}
                        <div>
                        @if (strtolower($booking->status) === 'confirmed' && now()->startOfDay()->isBefore($booking->check_in_date))
                        <form action="{{ route('profile.my-stays.cancel', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition">
                                ❌ Cancel Booking
                            </button>
                        </form>
                        @endif
                        </div>
                        {{-- Right Side: Awaiting/Review & Receipt Buttons --}}
                        <div class="flex items-center space-x-2">
                            @if($booking->review)
                                <span class="px-4 py-2 text-sm font-medium bg-green-200 text-green-800 rounded-lg">
                                    Reviewed
                                </span>
                            @elseif(now()->isAfter($booking->check_out_date) && strtolower($booking->status) !== 'cancelled')
                                <a href="{{ route('reviews.create', ['stayBooking' => $booking->id]) }}" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Leave a Review
                                </a>
                            @elseif(strtolower($booking->status) === 'confirmed' && now()->isBefore($booking->check_out_date))
                               <span class="px-4 py-2 text-sm font-medium bg-gray-200 text-gray-800 rounded-lg">
                                    Awaiting Stay
                                </span>
                            @endif
                            @if (in_array(strtolower($booking->status), ['confirmed', 'completed']))
                                <a href="{{ route('profile.my-stays.receipt', $booking) }}" class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                                    📄 Download Receipt
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
