@extends('layouts.app')

@section('content')
<div class="bg-gray-100 dark:bg-gray-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Page Header --}}
        <div class="text-center mb-10">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Book Your Pilgrim Stay</h1>
            <p class="mt-2 text-lg text-gray-600 dark:text-gray-400">Find comfortable accommodation near your destination temple.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- 1. FILTER SIDEBAR (LEFT) --}}
            <aside class="lg:col-span-1">
                <form action="{{ route('stays.index') }}" method="GET" class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg space-y-6">

                    {{-- Search by Name/Location --}}
                    <div>
                        <label for="search" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Search Hotel or City</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="e.g., 'Shanti Niwas'" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                    </div>

                    {{-- Filter by Temple --}}
                    <div>
                        <label for="temple_id" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Near Temple</label>
                        <select name="temple_id" id="temple_id" class="block w-full text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 rounded-md">
                            <option value="">All Locations</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}" {{ request('temple_id') == $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Facilities Filter (Now Functional) --}}
                    <div x-data="{ showAll: false }">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 border-b dark:border-gray-700 pb-2">
                            Facilities
                        </h4>

                        <div class="space-y-2">
                            @foreach($amenities as $index => $amenity)
                                <div
                                    class="flex items-center"
                                    x-show="{{ $index < 2 ? 'true' : 'showAll' }}"
                                >
                                    <input id="facility_{{ $amenity->id }}"
                                        name="facilities[]"
                                        value="{{ $amenity->name }}"
                                        type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500"
                                        {{ is_array(request('facilities')) && in_array($amenity->name, request('facilities')) ? 'checked' : '' }}>
                                    <label for="facility_{{ $amenity->id }}" class="ml-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- View More / View Less --}}
                        @if(count($amenities) > 2)
                            <button type="button"
                                    class="mt-3 text-sm text-indigo-600 hover:underline dark:text-indigo-400"
                                    @click="showAll = !showAll">
                                <span x-text="showAll ? 'View Less' : 'View More'"></span>
                            </button>
                        @endif
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                            Apply Filters
                        </button>
                        <a href="{{ route('stays.index') }}" class="mt-2 w-full inline-flex justify-center py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Reset
                        </a>
                    </div>
                </form>
            </aside>

            {{-- ============================ --}}
            {{-- 2. HOTEL RESULTS (RIGHT) --}}
            {{-- ============================ --}}
            <main class="lg:col-span-3">
                @if($hotels->isEmpty())
                    <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-xl shadow-lg">
                        <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-200">No Accommodations Found</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search filters.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($hotels as $hotel)
                        <div class="bg-white dark:bg-gray-900 shadow-lg rounded-xl overflow-hidden md:flex transition-shadow duration-300 hover:shadow-2xl border dark:border-gray-800">

                            {{-- Image Section --}}
                            <div class="md:w-1/3">
                                <img src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/600x400/1a1a1a/444444?text=DivyaDarshan' }}"
                                     alt="Image of {{ $hotel->name }}"
                                     class="h-full w-full object-cover">
                            </div>

                            {{-- Details Section --}}
                            <div class="p-6 md:w-2/3 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $hotel->name }}</h3>
                                            <p class="text-sm text-yellow-500 font-semibold mb-2">{{ $hotel->location }}</p>
                                        </div>
                                        {{-- review data --}}
                                        <div class="flex-shrink-0 ml-4 text-right">
                                            <div class="px-3 py-1 bg-blue-700 text-white text-lg font-bold rounded-md">4.5</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">120 reviews</div>
                                        </div>
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-2">
                                        {{ Str::limit($hotel->description, 150) }}
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-end items-center">
                                    <a href="{{ route('stays.show', $hotel) }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 font-semibold transition-colors duration-300">
                                        Show Prices & Details
                                    </a>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $hotels->appends(request()->query())->links() }}
                    </div>
                @endif
            </main>

        </div>
    </div>
</div>
@endsection
