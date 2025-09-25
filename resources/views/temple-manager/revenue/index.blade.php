@extends('layouts.temple-manager')

@section('content')
<style>
    .summary-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .summary-card h3 {
        font-size: 14px;
        font-weight: 500;
        color: #6b7280;
    }

    .summary-card p {
        font-size: 24px;
        font-weight: bold;
        margin-top: 6px;
        color: #111827;
    }

    .filter-wrapper label {
        font-size: 14px;
        font-weight: 500;
        color: #374151;
    }

    .filter-wrapper input[type="date"] {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: white;
    }

    .btn-filter {
        padding: 10px 16px;
        background-color: #4f46e5;
        color: white;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-filter:hover {
        background-color: #4338ca;
    }
</style>

<div class="container mx-auto px-4 py-8">
    {{-- Page Title --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-800 dark:text-white">
            Revenue for <span class="text-indigo-600 dark:text-indigo-400">{{ $temple->name }}</span>
        </h1>
    </div>

    {{-- Filter --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md mb-8">
        <form action="{{ route('temple-manager.revenue.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div class="filter-wrapper">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}">
            
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}">
            
                <button type="submit" class="btn-filter w-full sm:w-auto">Filter</button>
            </div>
        </form>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="summary-card">
            <h3>Darshan Revenue</h3>
            <p>₹{{ number_format($darshanRevenue, 2) }}</p>
        </div>
        <div class="summary-card">
            <h3>Seva Revenue</h3>
            <p>₹{{ number_format($sevaRevenue, 2) }}</p>
        </div>
        <div class="summary-card">
            <h3>Donation Revenue</h3>
            <p>₹{{ number_format($donationRevenue, 2) }}</p>
        </div>
        <div class="summary-card bg-indigo-600 text-white">
            <h3>Total Revenue</h3>
            <p>₹{{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="space-y-12">
        {{-- Darshan Bookings --}}
        <section>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Darshan Bookings</h2>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Booking ID</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Devotees</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($darshanBookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">#{{ $booking->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $booking->booking_date->format('d M, Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $booking->number_of_people }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600 dark:text-indigo-400">₹{{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No Darshan bookings found.</td>
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
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Seva Bookings</h2>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Seva Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($sevaBookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ $booking->seva->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $booking->created_at->format('d M, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600 dark:text-indigo-400">₹{{ number_format($booking->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No Seva bookings found.</td>
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
            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Recent Donations</h2>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Purpose</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($donations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">{{ $donation->donation_purpose }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $donation->created_at->format('d M, Y') }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-indigo-600 dark:text-indigo-400">₹{{ number_format($donation->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No donations found.</td>
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
