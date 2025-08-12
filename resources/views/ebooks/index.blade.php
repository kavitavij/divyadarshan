@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Our eBooks</h1>

    @if($ebooks->isEmpty())
        <p class="text-center text-gray-500">No eBooks are available at the moment. Please check back later.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($ebooks as $ebook)
                <div class="card bg-white shadow-lg rounded-lg overflow-hidden">
                    <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank">
                        @if($ebook->cover_image_path)
                            <img src="{{ Storage::url($ebook->cover_image_path) }}" alt="{{ $ebook->title }} Cover" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500">No Cover</span>
                            </div>
                        @endif
                    </a>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $ebook->title }}</h3>
                        @if($ebook->author)
                            <p class="text-gray-600 text-sm mb-4">By: {{ $ebook->author }}</p>
                        @endif
                        <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full text-center">
                            Read PDF
                        </a>
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