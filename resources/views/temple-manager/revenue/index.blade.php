@extends('layouts.temple-manager')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 dark:text-white tracking-tight">
        Revenue for <span class="text-indigo-600 dark:text-indigo-400">{{ $temple->name }}</span>
    </h1>

    {{-- Date Filter Form --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md mb-6">
    <form action="{{ route('temple-manager.revenue.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end sm:space-x-6 space-y-4 sm:space-y-0">
                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">

                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">

                <button type="submit" class="bg-blue-600 text-black px-4 py-2 rounded-md hover:bg-blue-700">Filter</button>
            </div>
        </form>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Darshan Revenue</h3>
            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($darshanRevenue, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Seva Revenue</h3>
            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($sevaRevenue, 2) }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400">Donation Revenue</h3>
            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">₹{{ number_format($donationRevenue, 2) }}</p>
        </div>
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-400 text-black p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold tracking-wide">Total Revenue</h3>
            <p class="mt-3 text-3xl font-extrabold tracking-tight">₹{{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    {{-- Tables Container --}}
    <div class="space-y-12">

        {{-- Darshan Bookings --}}
        <section>
            <h2 class="text-2xl font-extrabold mb-6 text-gray-900 dark:text-white tracking-tight">Recent Darshan Bookings</h2>
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Devotees</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($darshanBookings as $booking)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#{{ $booking->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $booking->booking_date->format('d M, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $booking->number_of_people }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700 dark:text-indigo-400">₹{{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">No Darshan bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $darshanBookings->appends(request()->except('darshan_page'))->links('pagination::tailwind') }}
            </div>
        </section>

        {{-- Seva Bookings --}}
        <section>
            <h2 class="text-2xl font-extrabold mb-6 text-gray-900 dark:text-white tracking-tight">Recent Seva Bookings</h2>
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Seva Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($sevaBookings as $booking)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $booking->seva->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $booking->created_at->format('d M, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700 dark:text-indigo-400">₹{{ number_format($booking->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">No Seva bookings found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $sevaBookings->appends(request()->except('seva_page'))->links('pagination::tailwind') }}
            </div>
        </section>

        {{-- Donations --}}
        <section>
            <h2 class="text-2xl font-extrabold mb-6 text-gray-900 dark:text-white tracking-tight">Recent Donations</h2>
            <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-auto">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Purpose</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($donations as $donation)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-900 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $donation->donation_purpose }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $donation->created_at->format('d M, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-700 dark:text-indigo-400">₹{{ number_format($donation->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">No donations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $donations->appends(request()->except('donation_page'))->links('pagination::tailwind') }}
            </div>
        </section>

    </div>
</div>
@endsection
