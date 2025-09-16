@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Donations Management</h1>

        <!-- Search & Filter Form -->
        <form action="{{ route('admin.donations.index') }}" method="GET"
              class="w-full md:w-auto flex items-center gap-2">

 <!-- Search & Filter Form -->
<form action="{{ route('admin.donations.index') }}" method="GET"
      class="w-full md:w-auto flex flex-col md:flex-row items-start md:items-center gap-4">

    <!-- Search Input -->
    <div class="relative">
        <input type="text" name="search" placeholder="Search donations..." value="{{ request('search') }}" class="w-full md:w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
    </div>

    <!-- Date Range -->
    <div class="flex items-center gap-2">
        <input type="date" name="from_date" value="{{ request('from_date') }}"
               class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <span class="text-gray-500">to</span>
        <input type="date" name="to_date" value="{{ request('to_date') }}"
               class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Temple Dropdown -->
    <select name="temple_id"
            class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <option value="">All Temples</option>
        @foreach($temples as $temple)
            <option value="{{ $temple->id }}" {{ request('temple_id') == $temple->id ? 'selected' : '' }}>
                {{ $temple->name }}
            </option>
        @endforeach
    </select>

    <!-- Amount Range -->
    <div class="flex items-center gap-2">
        <input type="number" step="0.01" name="min_amount" value="{{ request('min_amount') }}"
               placeholder="Min Amount"
               class="w-28 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <span class="text-gray-500">-</span>
        <input type="number" step="0.01" name="max_amount" value="{{ request('max_amount') }}"
               placeholder="Max Amount"
               class="w-28 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
    </div>

    <!-- Buttons -->
    <div class="flex gap-2">
        <button type="submit"
                class="bg-blue-600 text-black px-5 py-2 rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
            Apply Filters
        </button>

        <a href="{{ route('admin.donations.index') }}"
           class="bg-gray-500 text-black px-5 py-2 rounded-lg hover:bg-gray-600 transition font-medium shadow-sm">
            Reset
        </a>

        <!-- Export Button -->
        <button type="submit" formaction="{{ route('admin.donations.export') }}"
                class="bg-green-600 text-black px-5 py-2 rounded-lg hover:bg-green-700 transition font-medium shadow-sm flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Export
        </button>
    </div>
</form>


    <!-- Table Container -->
    <div class="bg-transparent">
        <table class="min-w-full border-separate" style="border-spacing: 0 10px;">
            <thead class="hidden lg:table-header-group">
                <tr class="">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Devotee</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Donation Target</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-transparent">
                @forelse ($donations as $donation)
                    <tr class="bg-white shadow-md rounded-lg hover:shadow-lg transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 align-middle">
                            #{{ $donation->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap align-middle">
                            <p class="font-semibold text-gray-900 text-sm">{{ $donation->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $donation->user->email ?? 'Email not available' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700 align-middle">
                            ₹{{ number_format($donation->amount, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm align-middle">
                            <p class="font-medium text-gray-800">{{ $donation->temple->name ?? 'General Donation' }}</p>
                            @if($donation->purpose)
                                <p class="text-xs text-gray-500">
                                    Purpose: <span class="font-medium">{{ Illuminate\Support\Str::title(str_replace('_', ' ', $donation->purpose)) }}</span>
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 align-middle">
                            {{ $donation->created_at->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center align-middle">
                            <a href="{{ route('admin.donations.show', $donation) }}"
                               class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg text-xs font-semibold hover:bg-indigo-200 transition">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <div class="flex flex-col items-center bg-white py-8 rounded-lg shadow-md">
                                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                <p class="font-semibold">No Donations Found</p>
                                <p class="text-sm">No records match your current search or filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if ($donations->hasPages())
            <div class="mt-6">
                {{ $donations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
