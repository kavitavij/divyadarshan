@extends('layouts.admin')

@section('content')
    <h1>Upload New Ebook</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" x-data="{ type: 'free' }">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" name="author" class="form-control" id="author" value="{{ old('author') }}">
        </div>
        <div class="mb-3">
            <label for="language" class="form-label">Language</label>
            <select name="language" id="language" class="form-select">
                <option value="English" @if(old('language') == 'English') selected @endif>English</option>
                <option value="Hindi" @if(old('language') == 'Hindi') selected @endif>Hindi</option>
                <option value="Tamil" @if(old('language') == 'Tamil') selected @endif>Tamil</option>
                <option value="Telugu" @if(old('language') == 'Telugu') selected @endif>Telugu</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" x-model="type">
                <option value="free">Free</option>
                <option value="paid">Paid</option>
            </select>
        </div>
        <div x-show="type === 'paid'" x-transition>
            <div class="mb-3">
                <label for="price" class="form-label">Price (₹)</label>
                <input type="number" name="price" class="form-control" id="price" value="{{ old('price') }}" placeholder="e.g., 99.00" step="0.01">
            </div>
            <div class="mb-3">
                <label for="discount_percentage" class="form-label">Discount (%)</label>
                <input type="number" name="discount_percentage" class="form-control" id="discount_percentage" value="{{ old('discount_percentage', 0) }}" placeholder="e.g., 10" step="0.01" min="0" max="100">
            </div>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" name="cover_image" class="form-control" id="cover_image">
        </div>
        <div class="mb-3">
            <label for="ebook_file" class="form-label">Ebook PDF</label>
            <input type="file" name="ebook_file" class="form-control" id="ebook_file" required>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </form>
    
    {{-- Add AlpineJS for the show/hide price field functionality --}}
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection