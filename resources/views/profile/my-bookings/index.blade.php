@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-extrabold mb-8 text-gray-800 dark:text-gray-200 text-center">
                🛕 My Darshan Bookings
            </h1>

            @if ($bookings->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
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
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($bookings as $booking)
                        <div
                            class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition">
                            <!-- Temple -->
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                🛕 {{ $booking->temple->name }}
                            </h2>

                            <!-- Booking Info -->
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                <p><span class="font-semibold">👥 Devotees:</span> {{ $booking->number_of_people }}</p>
                                <p>
                                    <span class="font-semibold">📅 Booked On:</span>
                                    {{ $booking->created_at->format('d M Y, h:i A') }}
                                </p>
                                <p>
                                    <span class="font-semibold">📌 Status:</span>
                                    @php
                                        $statusClass =
                                            $booking->status === 'Confirmed'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </p>
                            </div>

                            <!-- Action -->
                            <div class="mt-4 flex justify-end">
                                {{-- CORRECTED: The route name is now correct and the $booking object is passed --}}
                                <a href="{{ route('profile.my-bookings.receipt.download', $booking) }}"
                                    class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                                    📄 View Receipt
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
