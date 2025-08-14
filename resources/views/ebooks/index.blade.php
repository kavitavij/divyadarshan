@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-4">ePublications</h1>

    {{-- Filter Menus --}}
    <div class="flex flex-col md:flex-row justify-center gap-4 mb-8">
        <div class="flex items-center gap-2">
            <span class="font-semibold">Language:</span>
            <a href="{{ route('ebooks.index', ['type' => $selectedType]) }}" class="px-3 py-1 rounded {{ !$selectedLanguage ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</a>
            @foreach($languages as $language)
                <a href="{{ route('ebooks.index', ['language' => $language, 'type' => $selectedType]) }}" class="px-3 py-1 rounded {{ $selectedLanguage == $language ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">{{ $language }}</a>
            @endforeach
        </div>
        <div class="flex items-center gap-2">
            <span class="font-semibold">Type:</span>
            <a href="{{ route('ebooks.index', ['language' => $selectedLanguage]) }}" class="px-3 py-1 rounded {{ !$selectedType ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">All</a>
            <a href="{{ route('ebooks.index', ['type' => 'free', 'language' => $selectedLanguage]) }}" class="px-3 py-1 rounded {{ $selectedType == 'free' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Free</a>
            <a href="{{ route('ebooks.index', ['type' => 'paid', 'language' => $selectedLanguage]) }}" class="px-3 py-1 rounded {{ $selectedType == 'paid' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">Paid</a>
        </div>
    </div>

    @if($ebooks->isEmpty())
        <p class="text-center text-gray-500">No eBooks match your selected filters. Please try a different selection.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {{-- Replace your existing loop with this one --}}
@foreach($ebooks as $ebook)
    <div class="card bg-white shadow-lg rounded-lg overflow-hidden flex flex-col">
        <div class="relative">
            <img src="{{ $ebook->cover_image_path ? Storage::url($ebook->cover_image_path) : 'https://via.placeholder.com/400x300.png/EBF4FF/7F9CF5?text=No+Cover' }}" alt="{{ $ebook->title }} Cover" class="w-full h-48 object-cover">
            {{-- Paid/Free Badge --}}
            <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-bold {{ $ebook->type == 'paid' ? 'bg-amber-500 text-white' : 'bg-green-600 text-white' }}">
                {{ ucfirst($ebook->type) }}
            </div>
        </div>
        <div class="p-4 flex flex-col flex-grow">
            <h3 class="font-bold text-lg mb-2">{{ $ebook->title }}</h3>
            @if($ebook->author)
                <p class="text-gray-600 text-sm mb-4">By: {{ $ebook->author }}</p>
            @endif
            {{-- Spacer to push the button to the bottom --}}
            <div class="flex-grow"></div>
            {{-- Price and Button --}}
            <div class="mt-4">
                @if($ebook->type == 'paid')
                    <p class="text-xl font-bold text-blue-700 mb-2">₹{{ $ebook->price }}</p>
                    {{-- THE FIX: This form now correctly wraps the "Buy Now" button --}}
                    <form action="{{ route('ebooks.purchase', $ebook) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-block bg-amber-500 text-white px-4 py-2 rounded hover:bg-amber-600 w-full text-center">
                            Buy Now
                        </button>
                    </form>
                @else
                    <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full text-center">
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