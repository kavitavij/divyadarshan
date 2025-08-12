@extends('layouts.admin')

@section('content')
    <h1>Upload New Ebook</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3"><label for="title" class="form-label">Title</label><input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}"></div>
        <div class="mb-3"><label for="author" class="form-label">Author</label><input type="text" name="author" class="form-control" id="author" value="{{ old('author') }}"></div>
        <div class="mb-3"><label for="cover_image" class="form-label">Cover Image</label><input type="file" name="cover_image" class="form-control" id="cover_image"></div>
        <div class="mb-3"><label for="ebook_file" class="form-label">Ebook PDF</label><input type="file" name="ebook_file" class="form-control" id="ebook_file" required></div>
        <div class="text-end"><a href="{{ route('admin.ebooks.index') }}" class="btn btn-secondary">Back</a><button type="submit" class="btn btn-primary">Upload</button></div>
    </form>
@endsection