@extends('layouts.app')

@section('content')
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-gray-200 border-b pb-4 dark:border-gray-700">My
                Purchased eBooks</h1>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-md relative"
                    role="alert">
                    {{ session('info') }}
                </div>
            @endif

            @if ($ebooks->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v1.5M12 11.25v6.5M18.75 12a6.75 6.75 0 11-13.5 0 6.75 6.75 0 0113.5 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-gray-200">No eBooks Found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">You have not purchased any eBooks yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('ebooks.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                            Browse eBooks
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($ebooks as $ebook)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                            <img src="{{ $ebook->cover_image_path ? Storage::url($ebook->cover_image_path) : 'https://via.placeholder.com/400x300.png/EBF4FF/7F9CF5?text=No+Cover' }}"
                                alt="{{ $ebook->title }} Cover" class="w-full h-48 object-cover">

                            <div class="p-6">
                                <h2 class="text-xl font-bold mb-2 text-gray-800 dark:text-white">{{ $ebook->title }}</h2>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 h-20">
                                    {{ Str::limit($ebook->description, 100) }}</p>
                                <a href="{{ asset('storage/' . $ebook->ebook_file_path) }}" target="_blank"
   class="block w-full text-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
    Read PDF
</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
