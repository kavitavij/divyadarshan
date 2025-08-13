@extends('layouts.app')
@section('content')
    <h1 class="text-3xl font-bold text-center">Our eBooks</h1>
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mt-4">
        @foreach($ebooks as $ebook)
            <div class="card">
                @if($ebook->cover_image_path)
                     {{-- THE FIX --}}
                    <img src="{{ Storage::url($ebook->cover_image_path) }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                    <h3>{{ $ebook->title }}</h3>
                    {{-- THE FIX --}}
                    <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank">Read PDF</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection