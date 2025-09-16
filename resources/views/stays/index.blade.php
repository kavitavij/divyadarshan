@extends('layouts.app')

@section('title', 'Book Your Pilgrim Stay')

@section('content')
<div class="bg-gray-100 dark:bg-gray-800" x-data="{ filtersOpen: false }">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Page Header --}}
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white">Book Your Pilgrim Stay</h1>
            <p class="mt-2 text-md md:text-lg text-gray-600 dark:text-gray-400">Find comfortable accommodation near your destination temple.</p>
        </div>

        {{-- ✅ MOBILE: "Show Filters" Button (Only visible on mobile/tablet) --}}
        <div class="lg:hidden mb-6">
             <button @click="filtersOpen = true" class="w-full flex justify-center items-center py-3 px-4 shadow-sm text-lg font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-400 focus:outline-none">
                <i class="fas fa-filter mr-2"></i>
                Show Filters
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- ✅ DESKTOP: Filter Sidebar (Only visible on desktop) --}}
            <aside class="hidden lg:block lg:col-span-1">
                 <form action="{{ route('stays.index') }}" method="GET" class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg space-y-6 sticky top-24">
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

                    {{-- Facilities Filter --}}
                    <div x-data="{ showAll: false }">
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 border-b dark:border-gray-700 pb-2">Facilities</h4>
                        <div class="space-y-2">
                            @foreach($amenities as $index => $amenity)
                                <div class="flex items-center" x-show="{{ $index < 4 ? 'true' : 'showAll' }}" x-transition>
                                    <input id="facility_{{ $amenity->id }}" name="facilities[]" value="{{ $amenity->name }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-yellow-600 focus:ring-yellow-500" {{ is_array(request('facilities')) && in_array($amenity->name, request('facilities')) ? 'checked' : '' }}>
                                    <label for="facility_{{ $amenity->id }}" class="ml-3 text-sm text-gray-600 dark:text-gray-300">{{ $amenity->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        @if(count($amenities) > 4)
                            <button type="button" class="mt-3 text-sm text-indigo-600 hover:underline dark:text-indigo-400" @click="showAll = !showAll">
                                <span x-text="showAll ? 'View Less' : 'View More'"></span>
                            </button>
                        @endif
                    </div>

                    <div class="pt-4 border-t dark:border-gray-700">
                        <button type="submit" class="w-full justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">Apply Filters</button>
                        <a href="{{ route('stays.index') }}" class="mt-2 w-full inline-flex justify-center py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300">Reset</a>
                    </div>
                 </form>
            </aside>

            {{-- Main Content --}}
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
                                            @if($hotel->reviews->count() > 0)
                                                @php
                                                    $averageRating = $hotel->reviews->avg('rating');
                                                @endphp
                                                <div class="px-3 py-1 text-white text-lg font-bold rounded-md
                                                    @if($averageRating >= 4) bg-green-600
                                                    @elseif($averageRating >= 3) bg-yellow-500
                                                    @else bg-red-600
                                                    @endif">
                                                    {{ number_format($averageRating, 1) }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $hotel->reviews->count() }} {{ Str::plural('review', $hotel->reviews->count()) }}
                                                </div>
                                            @else
                                                <div class="px-3 py-1 bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-sm font-semibold rounded-md">
                                                    New
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">No reviews</div>
                                            @endif
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

    {{-- ✅ MOBILE: Filter Modal --}}
    <div x-show="filtersOpen" x-cloak class="fixed inset-0 z-50 flex lg:hidden" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click="filtersOpen = false" class="fixed inset-0 bg-black bg-opacity-60"></div>
        <div @click.away="filtersOpen = false" class="relative bg-white dark:bg-gray-900 rounded-t-xl shadow-2xl w-full max-h-[85vh] flex flex-col mt-auto">
            <div class="p-4 flex items-center justify-between border-b dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-900">
                <h3 class="text-lg font-bold dark:text-white">Filter Accommodations</h3>
                <button @click="filtersOpen = false" class="p-2 -mr-2"><i class="fas fa-times text-xl dark:text-gray-300"></i></button>
            </div>
            <div class="p-6 overflow-y-auto">
                <form action="{{ route('stays.index') }}" method="GET" class="space-y-6">
                    {{-- (The same filter content is duplicated here for the mobile modal) --}}
                    <div>
                        <label for="search_mobile" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Search Hotel or City</label>
                        <input type="text" name="search" id="search_mobile" value="{{ request('search') }}" placeholder="e.g., 'Shanti Niwas'" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-md shadow-sm">
                    </div>
                     <div>
                        <label for="temple_id_mobile" class="block text-sm font-bold text-gray-700 dark:text-gray-200 mb-2">Near Temple</label>
                        <select name="temple_id" id="temple_id_mobile" class="block w-full text-base border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 rounded-md">
                            <option value="">All Locations</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}" {{ request('temple_id') == $temple->id ? 'selected' : '' }}>{{ $temple->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-data="{ showAll: true }"> {{-- Show all by default in modal --}}
                        <h4 class="text-sm font-bold text-gray-700 dark:text-gray-200 mb-3 border-b dark:border-gray-700 pb-2">Facilities</h4>
                        <div class="space-y-2">
                            @foreach($amenities as $amenity)
                                <div class="flex items-center">
                                    <input id="facility_mobile_{{ $amenity->id }}" name="facilities[]" value="{{ $amenity->name }}" type="checkbox" class="h-4 w-4 rounded border-gray-300" {{ is_array(request('facilities')) && in_array($amenity->name, request('facilities')) ? 'checked' : '' }}>
                                    <label for="facility_mobile_{{ $amenity->id }}" class="ml-3 text-sm text-gray-600 dark:text-gray-300">{{ $amenity->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="w-full justify-center py-3 px-4 shadow-sm text-lg font-medium rounded-md text-black bg-yellow-500 hover:bg-yellow-400">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
