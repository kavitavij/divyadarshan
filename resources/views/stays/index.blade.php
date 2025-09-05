@extends('layouts.app')

@section('content')
<div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-extrabold text-center mb-4 text-gray-800 dark:text-gray-100">Book Your Pilgrim Stay</h1>
        <p class="text-center text-gray-600 dark:text-gray-400 mb-10">Find comfortable and convenient accommodation near your destination temple.</p>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg mb-10">
            <form action="{{ route('stays.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                {{-- Filter by Temple --}}
                <div class="md:col-span-1">
                    <label for="temple_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Temple</label>
                    <select name="temple_id" id="temple_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 sm:text-sm rounded-md">
                        <option value="">All Temples</option>
                        @foreach($temples as $temple)
                            <option value="{{ $temple->id }}" {{ request('temple_id') == $temple->id ? 'selected' : '' }}>
                                {{ $temple->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search by Name/Location --}}
                <div class="md:col-span-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search by Name or City</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="e.g., 'Shanti Niwas' or 'Rishikesh'" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                {{-- Action Buttons --}}
                <div class="md:col-span-1 flex gap-2">
                    <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Search
                    </button>
                    <a href="{{ route('stays.index') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @if($hotels->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400 py-16">No stays found matching your criteria. Please try different filters.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($hotels as $hotel)
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden flex flex-col group transform hover:-translate-y-1 transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:border-yellow-500">
                        <div class="relative">
                            {{-- Handle both public/uploads and storage/app/public --}}
                            <img src="{{ $hotel->image
                                ? (Str::startsWith($hotel->image, 'http')
                                    ? $hotel->image
                                    : (file_exists(public_path($hotel->image))
                                        ? asset($hotel->image)
                                        : asset('storage/' . $hotel->image)))
                                : 'https://placehold.co/600x400/EBF4FF/7F9CF5?text=Hotel' }}"
                                alt="{{ $hotel->name }}" class="w-full h-52 object-cover">

                            @if($hotel->temple)
                               <span class="absolute top-3 left-3 bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded">
                                   Near {{ $hotel->temple->name }}
                               </span>
                            @endif
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="font-bold text-xl mb-1 text-gray-900 dark:text-white">{{ $hotel->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $hotel->location }}</p>

                            {{-- Displaying starting price --}}
                            @if($hotel->rooms_min_price_per_night)
                                <div class="mb-4">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Rooms from</span>
                                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        ₹{{ number_format($hotel->rooms_min_price_per_night) }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">/ night</span>
                                </div>
                            @endif

                            <p class="text-gray-700 dark:text-gray-300 text-sm flex-grow">{{ Str::limit($hotel->description, 90) }}</p>

                            <div class="mt-auto pt-5">
                                <a href="{{ route('stays.show', $hotel) }}" class="inline-block bg-indigo-600 text-white px-5 py-3 rounded-lg hover:bg-indigo-700 w-full text-center font-semibold group-hover:bg-yellow-500 group-hover:text-black transition-colors duration-300">
                                    View Rooms & Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $hotels->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
