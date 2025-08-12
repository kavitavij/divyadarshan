@extends('layouts.admin')

@section('content')
    <h1>Edit Ebook</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.ebooks.update', $ebook->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3"><label for="title" class="form-label">Title</label><input type="text" name="title" class="form-control" id="title" value="{{ $ebook->title }}"></div>
        <div class="mb-3"><label for="author" class="form-label">Author</label><input type="text" name="author" class="form-control" id="author" value="{{ $ebook->author }}"></div>
        <div class="mb-3"><label class="form-label">Current Cover</label><div>@if($ebook->cover_image_path)<img src="{{ Storage::url($ebook->cover_image_path) }}" height="100" class="mb-2">@else<p>No cover image.</p>@endif</div><label for="cover_image" class="form-label">New Cover</label><input type="file" name="cover_image" class="form-control" id="cover_image"></div>
        <div class="mb-3"><label class="form-label">Current PDF</label><div><a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank">View Current PDF</a></div><label for="ebook_file" class="form-label">New PDF</label><input type="file" name="ebook_file" class="form-control" id="ebook_file"></div>
        <div class="text-end"><a href="{{ route('admin.ebooks.index') }}" class="btn btn-secondary">Back</a><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
@endsection