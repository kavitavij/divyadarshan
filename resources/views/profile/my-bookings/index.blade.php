@extends('layouts.app')

@section('content')
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-10">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-extrabold mb-8 text-gray-800 dark:text-gray-200 text-center">
            My Darshan Bookings
        </h1>

        @if ($bookings->isEmpty())
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-200">No Bookings Found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You have not made any bookings yet.</p>
                <div class="mt-6">
                    <a href="{{ route('booking.index') }}"
                        class="inline-flex items-center px-5 py-2 rounded-lg shadow bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                        ➕ Book a Darshan Now
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($bookings as $booking)
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-shadow duration-300">
        <div class="flex-grow">
            {{-- Header with Temple Name and Status --}}
            <div class="flex justify-between items-start mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    🛕 {{ $booking->temple->name }}
                </h2>
                @php
                    $statusClass = $booking->status === 'Confirmed'
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                @endphp
                <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusClass }}">
                    {{ $booking->status }}
                </span>
            </div>

            {{-- Booking Details section --}}
            <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300 border-t border-gray-200 dark:border-gray-700 pt-4">
                {{-- This is now the ONLY line that displays the Darshan Date --}}
                <p><span class="font-semibold text-gray-500 dark:text-gray-400">🗓️ Darshan Date:</span> {{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</p>
<p>
    <span class="font-semibold text-gray-500 dark:text-gray-400">🕔 Time Slot:</span>
    @if($booking->darshanSlot)
        {{ \Carbon\Carbon::parse($booking->darshanSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->darshanSlot->end_time)->format('h:i A') }}
    @elseif($booking->defaultDarshanSlot)
        {{ \Carbon\Carbon::parse($booking->defaultDarshanSlot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->defaultDarshanSlot->end_time)->format('h:i A') }}
    @else
        {{ $booking->time_slot ?? 'N/A' }}
    @endif
</p>                <p><span class="font-semibold text-gray-500 dark:text-gray-400">👥 Devotees:</span> {{ $booking->number_of_people }}</p>
                <p><span class="font-semibold text-gray-500 dark:text-gray-400">✅ Booked On:</span> {{ $booking->created_at->format('F j, Y, h:i A') }}</p>
            </div>
        </div>

        {{-- Actions with Cancel and Receipt buttons --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
            @if ($booking->status === 'Confirmed' && \Carbon\Carbon::parse($booking->booking_date)->endOfDay()->isFuture())
                <form action="{{ route('profile.my-bookings.cancel', $booking) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium bg-red-600 text-white rounded-lg shadow hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        ❌ Cancel Booking
                    </button>
                </form>
            @endif

            </div>
            <a href="{{ route('profile.my-bookings.receipt.download', $booking) }}"
                class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                📄 View Receipt
            </a>
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
