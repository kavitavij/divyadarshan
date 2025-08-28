@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-gray-200 border-b pb-4 dark:border-gray-700">My
                Darshan Bookings</h1>

            @if ($bookings->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">No Bookings Found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You have not made any bookings yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('booking.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            Book a Darshan Now
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <!-- Table Headers (Visible on Desktop only) -->
                    <div
                        class="hidden md:flex bg-gray-50 dark:bg-gray-700 px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        <div class="w-1/3">Temple Name</div>
                        <div class="w-1/6">Devotees</div>
                        <div class="w-1/6">Status</div>
                        <div class="w-1/3">Booked On</div>
                    </div>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($bookings as $booking)
                            <div class="p-6 flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0">
                                <!-- Temple Name -->
                                <div class="w-full md:w-1/3">
                                    <div class="md:hidden text-xs font-bold uppercase text-gray-500">Temple</div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $booking->temple->name }}</div>
                                </div>
                                <!-- Devotees -->
                                <div class="w-full md:w-1/6">
                                    <div class="md:hidden text-xs font-bold uppercase text-gray-500">Devotees</div>
                                    <div class="text-sm text-gray-900 dark:text-gray-300">{{ $booking->number_of_people }}
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="w-full md:w-1/6">
                                    <div class="md:hidden text-xs font-bold uppercase text-gray-500">Status</div>
                                    @php
                                        $statusClass =
                                            $booking->status === 'Confirmed'
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                <!-- Booked On -->
                                <div class="w-full md:w-1/3">
                                    <div class="md:hidden text-xs font-bold uppercase text-gray-500">Booked On</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $booking->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
