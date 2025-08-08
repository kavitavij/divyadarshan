@extends('layouts.app')

@section('content')
<div class="mb-12">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Our Services</h2>
    <p class="text-center text-gray-600 mb-8">All essential services for your spiritual journey</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-4">
        @php
            $modules = [
                ['icon' => '🛕', 'title' => 'Temple Info', 'desc' => 'Browse temple details, timings, maps.'],
                ['icon' => '📅', 'title' => 'Book Darshan', 'desc' => 'Choose slots for special/general darshan.'],
                ['icon' => '🛌', 'title' => 'Accommodation', 'desc' => 'Book temple or partner hotel rooms.'],
                ['icon' => '🙏', 'title' => 'Sevas & Poojas', 'desc' => 'Participate in poojas online or in-person.'],
                ['icon' => '🚕', 'title' => 'Cab Booking', 'desc' => 'One-way, round trip, or temple packages.'],
                ['icon' => '💰', 'title' => 'Donations', 'desc' => 'Make donations with instant receipts.'],
                ['icon' => '📖', 'title' => 'E-Books', 'desc' => 'View and download spiritual texts.'],
                ['icon' => '🌐', 'title' => 'Languages', 'desc' => 'Available in Hindi, Tamil, Telugu & more.'],
            ];
        @endphp

        @foreach ($modules as $module)
            <div class="service-card bg-white p-6 rounded shadow text-center transition">
                <div class="text-4xl mb-2">{{ $module['icon'] }}</div>
                <h3 class="text-lg font-semibold text-blue-700">{{ $module['title'] }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $module['desc'] }}</p>
            </div>
        @endforeach

    </div>
</div>
<!-- 🛕 Temple Section -->
<div class="mb-12">
    <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">Temples</h2>

    <!-- 🔍 Search Bar -->
    <form method="GET" action="{{ route('home') }}" class="mb-6 flex justify-center">
        <input type="text" name="search" placeholder="Search temples..." value="{{ request('search') }}"
               class="border p-2 rounded w-full md:w-1/3 shadow">
    </form>
    
    <!-- 📜 Temple Cards -->
    @if($temples->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4">
            @foreach ($temples as $temple)
                <div class="bg-white p-4 rounded shadow hover:shadow-lg transition text-center max-w-xs w-full mx-auto flex flex-col h-full animate-fadeIn">
                    <img src="{{ asset('images/temples/' . $temple->image) }}"
                         alt="{{ $temple->name }}"
                         class="w-full h-48 object-cover rounded mb-3">

                    <h3 class="text-lg font-bold text-blue-700">{{ $temple->name }}</h3>
                    <p class="text-sm text-gray-600 mb-3">{{ $temple->location }}</p>

                    <div class="mt-auto">
                        <a href="{{ route('temples.show', $temple->id) }}" class="view-details-button">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $temples->links() }}
        </div>
    @else
        <p class="text-center text-gray-600">No temples found.</p>
    @endif
</div>



<!-- 🔔 Latest Updates Section -->
<div class="mb-12 px-4">
    <h2 class="text-xl font-semibold mb-4">Latest Updates</h2>
    <ul class="list-disc list-inside text-gray-700 space-y-2">
        <li>🔔 Special Darshan opened for Navratri week.</li>
        <li>🏨 New hotel partners added for Tirupati region.</li>
        <li>📖 New e-book on "Temple Rituals of South India" uploaded.</li>
        <li>🌐 Language support extended to Bengali and Marathi.</li>
    </ul>
</div>

@endsection
