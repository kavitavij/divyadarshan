@extends('layouts.app')

@section('content')
<div class="mb-12 px-4">
  <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">📚 E-Books</h2>

  <!-- Language Filter -->
  <div class="language-boxes flex gap-4 justify-center">
    @foreach($languages as $lang)
      <a href="{{ route('ebooks', ['lang' => $lang]) }}"
         class="language-box {{ $selectedLang == $lang ? 'active' : '' }}">
        {{ $lang }}
      </a>
    @endforeach
  </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4 mt-6">
        @forelse ($books as $book)
            <div class="bg-white p-4 rounded shadow hover:shadow-lg transition text-center">
                <img src="{{ asset('images/ebooks/' . $book['cover']) }}"
                     alt="{{ $book['title'] }}"
                     class="w-full h-48 object-cover rounded mb-3">
                <h3 class="text-lg font-bold text-blue-700 mb-2">{{ $book['title'] }}</h3>
                <p class="text-sm text-gray-600 mb-3">Language: {{ $book['lang'] }}</p>
                <a href="{{ asset('ebooks/' . $book['file']) }}" 
                   target="_blank"
                   class="">
                   📖 Read / Download
                </a>
            </div>
        @empty
            <p class="text-center text-gray-500 col-span-full">No books available in this language.</p>
        @endforelse
    </div>
</div>
@endsection
