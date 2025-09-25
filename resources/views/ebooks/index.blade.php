@extends('layouts.app')

@section('title', 'ePublications - DivyaDarshan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold text-center mb-8 text-gray-800 dark:text-white">ePublications</h1>

    <div class="flex flex-col sm:flex-row justify-center items-center gap-4 sm:gap-6 mb-8 p-4 bg-white dark:bg-gray-900 rounded-lg shadow-md">
        <div class="flex items-center gap-2 flex-wrap justify-center">
            <span class="font-semibold dark:text-gray-300">Language:</span>
            <a href="{{ route('ebooks.index', ['type' => $selectedType]) }}"
               class="px-3 py-1 rounded-full text-sm {{ !$selectedLanguage ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">All</a>
            @foreach ($languages as $language)
                <a href="{{ route('ebooks.index', ['language' => $language, 'type' => $selectedType]) }}"
                   class="px-3 py-1 rounded-full text-sm {{ $selectedLanguage == $language ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">{{ $language }}</a>
            @endforeach
        </div>
        <div class="flex items-center gap-2 flex-wrap justify-center">
            <span class="font-semibold dark:text-gray-300">Type:</span>
            <a href="{{ route('ebooks.index', ['language' => $selectedLanguage]) }}"
               class="px-3 py-1 rounded-full text-sm {{ !$selectedType ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">All</a>
            <a href="{{ route('ebooks.index', ['type' => 'free', 'language' => $selectedLanguage]) }}"
               class="px-3 py-1 rounded-full text-sm {{ $selectedType == 'free' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">Free</a>
            <a href="{{ route('ebooks.index', ['type' => 'paid', 'language' => $selectedLanguage]) }}"
               class="px-3 py-1 rounded-full text-sm {{ $selectedType == 'paid' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 dark:text-gray-300' }}">Paid</a>
        </div>
    </div>

    @if ($ebooks->isEmpty())
        <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-lg shadow-md">
            <p class="text-lg text-gray-500 dark:text-gray-400">No eBooks match your selected filters.</p>
            <p class="mt-2 text-gray-400">Please try a different selection.</p>
        </div>
    @else
        {{-- ✅ Grid is now responsive: 2 cols on mobile, up to 4 on large screens --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($ebooks as $ebook)
                <div class="card bg-white dark:bg-gray-900 shadow-lg rounded-lg overflow-hidden flex flex-col transition-transform duration-300 hover:-translate-y-2">
                    <div class="relative">
                        <img src="{{ $ebook->cover_image_path ? Storage::url($ebook->cover_image_path) : 'https://placehold.co/400x300/1a1a1a/444444?text=No+Cover' }}"
                             alt="{{ $ebook->title }} Cover" class="w-full h-48 object-cover">
                        <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold {{ $ebook->type == 'paid' ? 'bg-amber-500 text-white' : 'bg-green-600 text-white' }}">
                            {{ ucfirst($ebook->type) }}
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-2 dark:text-white">{{ $ebook->title }}</h3>
                        @if ($ebook->author)
                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">By: {{ $ebook->author }}</p>
                        @endif
                        <div class="flex-grow"></div> {{-- This pushes the button to the bottom --}}
                        <div class="mt-4">
                            @if ($ebook->type == 'paid')
                                <div class="mb-2">
                                    @if($ebook->discount_percentage > 0)
                                        <div class="flex items-center gap-2">
                                            <p class="text-xl font-bold text-blue-700 dark:text-blue-400">
                                                ₹{{ number_format($ebook->discounted_price, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-500 line-through">
                                                ₹{{ number_format($ebook->price, 2) }}
                                            </p>
                                        </div>
                                        <span class="text-xs font-semibold text-green-800 bg-green-200 px-2 py-0.5 rounded-full">
                                            {{ (int)$ebook->discount_percentage }}% OFF
                                        </span>
                                    @else
                                        <p class="text-xl font-bold text-blue-700 dark:text-blue-400">
                                            ₹{{ number_format($ebook->price, 2) }}
                                        </p>
                                    @endif
                                </div>
                                @auth
                                <form action="{{ route('cart.addEbook') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                                    <button type="submit" class="w-full text-black bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                                        Add to Cart
                                    </button>
                                </form>
                                @else
                                <button type="button" @click="loginModal = true" class="w-full text-black bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                                    Add to Cart
                                </button>
                                @endguest
                            @else
                                <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank" class="w-full block text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 text-center focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                                    Read PDF
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $ebooks->links() }}
        </div>
    @endif
</div>
@endsection

@if (session('success'))
    @push('scripts')
    <script>
        // Use a more modern, non-blocking notification if possible,
        // but alert() will work as a simple confirmation.
        alert("{{ session('success') }}");
    </script>
    @endpush
@endif
