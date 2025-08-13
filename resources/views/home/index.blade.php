@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="container mx-auto px-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
@endif
<div class="mb-12">
    <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Our Services</h2>
    <p class="text-center text-gray-600 mb-8">All essential services for your spiritual journey</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-4">
        @php
    $modules = [
        ['icon' => '🛕', 'title' => 'Temple Info', 'desc' => 'Browse temple details, timings, maps.', 'url' => $firstTemple ? route('temples.show', $firstTemple->id) : route('temples.index')],
        ['icon' => '📅', 'title' => 'Book Darshan', 'desc' => 'Choose slots for special/general darshan.', 'url' => url('/online-services')],
        ['icon' => '🛌', 'title' => 'Accommodation', 'desc' => 'Book temple or partner hotel rooms.', 'url' => '#'], 
        ['icon' => '🙏', 'title' => 'Sevas & Poojas', 'desc' => 'Participate in poojas online or in-person.', 'url' => '#'], 
        ['icon' => '🚕', 'title' => 'Cab Booking', 'desc' => 'One-way, round trip, or temple packages.', 'url' => url('/online-services')],
        ['icon' => '💰', 'title' => 'Donations', 'desc' => 'Make donations with instant receipts.', 'url' => '#'],
        ['icon' => '📖', 'title' => 'E-Books', 'desc' => 'View and download spiritual texts.', 'url' => route('ebooks.index')],
        ['icon' => '🌐', 'title' => 'Languages', 'desc' => 'Available in Hindi, Tamil, Telugu & more.', 'url' => route('ebooks.index')],
    ];
@endphp

@foreach ($modules as $module)
    <div class="service-card bg-white p-6 rounded shadow text-center transition">
        <button 
            type="button" 
            class="text-4xl mb-2 hover:text-blue-700 focus:outline-none"
            onclick="window.location.href='{{ $module['url'] }}'"
            aria-label="{{ $module['title'] }} button"
        >
            {{ $module['icon'] }}
        </button>

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
    @if($temples->total() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4">
        @foreach ($temples as $temple)
            <div class="bg-white p-4 rounded shadow hover:shadow-lg transition text-center max-w-xs w-full mx-auto flex flex-col h-full animate-fadeIn">
                <img src="{{ asset($temple->image) }}"
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
<!-- <div class="mb-12 px-4">
    <!-- Heading -->
    <!-- <h2 class="text-xl font-semibold mb-4 text-center text-blue-800">Latest Updates</h2> -->
    <!-- <div class="vertical-panel">
        <div class="vertical-scroll">
            <div class="notification-item">🔔 Special Darshan opened for Navratri week.</div>
            <div class="notification-item">🏨 New hotel partners added for Tirupati region.</div>
            <div class="notification-item">📖 New e-book on "Temple Rituals of South India" uploaded.</div>
            <div class="notification-item">🌐 Language support extended to Bengali and Marathi.</div>
        </div>
    </div>
</div> --> 
<div class="mb-12 px-4">
    <h2 class="text-xl font-semibold mb-4 text-center text-blue-800">Latest Updates</h2>

    <div class="vertical-panel">
        <div class="vertical-scroll">
            {{-- Check if there are any updates to show --}}
            @if(isset($latestUpdates) && $latestUpdates->isNotEmpty())
                {{-- Loop through each update from the database --}}
                @foreach ($latestUpdates as $update)
                    <div class="notification-item">{{ $update->message }}</div>
                @endforeach
            @else
                <div class="notification-item">No updates at the moment.</div>
            @endif
        </div>
    </div>
</div>
@endsection
<style>
.vertical-panel {
  width: 100%;
  max-width: 500px;
  height: 200px;
  margin: 0 auto;
  overflow: hidden;
  background-color: #fff8dc;
  border: 1px solid #ffd966;
  border-radius: 8px;
  padding: 10px;
  box-sizing: border-box;
  position: relative;
}

.vertical-scroll {
  display: flex;
  flex-direction: column;
  animation: scroll-up 30s linear infinite; /* Slower scroll */
  /* animation-play-state: running ensures it never stops */
}

.notification-item {
  display: block;
  text-decoration: none;
  padding: 12px 0;
  font-weight: 500;
  color: #7c4a03;
  font-size: 16px;
  text-align: center;
}

.notification-item:hover {
  background-color: rgba(124, 74, 3, 0.1);
}

@keyframes scroll-up {
  0%   { transform: translateY(100%); }
  100% { transform: translateY(-100%); }
}
</style>