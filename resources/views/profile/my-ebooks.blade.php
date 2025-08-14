    @extends('layouts.app')

    @section('content')
    <div class="container py-5">
        <h1 class="text-2xl font-bold mb-4">My Purchased eBooks</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @if($ebooks->isEmpty())
            <p>You have not purchased any eBooks yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($ebooks as $ebook)
                    <div class="card">
                        <img src="{{ asset($ebook->cover_image) }}" class="card-img-top" alt="{{ $ebook->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $ebook->title }}</h5>
                            <p class="card-text">{{ Str::limit($ebook->description, 100) }}</p>
                            <a href="{{ route('ebooks.download', $ebook) }}" class="btn btn-primary">Download</a>
                            {{-- You could add a "Read Online" button here too --}}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endsection
    