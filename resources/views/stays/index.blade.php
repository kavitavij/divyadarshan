@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Book Your Pilgrim Stay</h1>

    @if($hotels->isEmpty())
        <p class="text-center text-gray-500">No stays are listed at the moment. Please check back later.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($hotels as $hotel)
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden flex flex-col">
                    <img src="{{ $hotel->image ? asset($hotel->image) : 'https://placehold.co/600x400/EBF4FF/7F9CF5?text=Hotel' }}" alt="{{ $hotel->name }}" class="w-full h-48 object-cover">
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-2">{{ $hotel->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $hotel->location }}</p>
                        @if($hotel->temple)
                            <p class="text-xs text-gray-500 mb-4">Near: {{ $hotel->temple->name }}</p>
                        @endif
                        <p class="text-gray-700 text-sm flex-grow">{{ Str::limit($hotel->description, 100) }}</p>
                        <div class="mt-4">
                            <a href="{{ route('stays.show', $hotel) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full text-center">
                                View Rooms & Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $hotels->links() }}
        </div>
    @endif
</div>
@endsection
