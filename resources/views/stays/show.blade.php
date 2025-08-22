@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-2">{{ $hotel->name }}</h1>
        <p class="text-gray-600 mb-6">{{ $hotel->location }}</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column: Hotel Details --}}
            <div class="lg:col-span-2">
                <img src="{{ $hotel->image ? asset($hotel->image) : 'https://placehold.co/800x500/EBF4FF/7F9CF5?text=Hotel' }}"
                    alt="{{ $hotel->name }}" class="w-full rounded-lg shadow-md mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">About this Hotel</h2>
                    <p class="text-gray-700">{{ $hotel->description }}</p>
                </div>
            </div>

            {{-- Right Column: Available Rooms --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-semibold mb-4">Available Rooms</h2>
                    <div class="space-y-4">
                        @forelse($hotel->rooms as $room)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-bold text-lg">{{ $room->type }}</h3>
                                <p class="text-sm text-gray-600">Capacity: {{ $room->capacity }} guests</p>
                                <p class="text-xl font-semibold text-blue-700 mt-2">
                                    ₹{{ number_format($room->price_per_night, 2) }} <span
                                        class="text-sm font-normal text-gray-500">/ night</span></p>
                                <div class="mt-3">
                                    {{-- THE FIX: Show different buttons for guests and logged-in users --}}
                                    @auth
                                        {{-- This button shows for LOGGED-IN users --}}
                                        <a href="{{ route('stays.details', $room) }}" class="btn btn-success w-full">Book
                                            Now</a>
                                    @else
                                        {{-- This button shows for GUESTS and opens the login modal --}}
                                        <button type="button" @click="loginModal = true; modalView = 'login'"
                                            class="btn btn-primary w-full">
                                            Login to Book
                                        </button>
                                    @endguest
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">There are no rooms listed for this hotel yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
