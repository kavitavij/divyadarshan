@extends('layouts.temple-manager')

@section('content')
<div class="container-fluid">
    <h1>Manage Temple Gallery</h1>
    <p>Upload new images or remove existing ones for <strong>{{ $temple->name }}</strong>.</p>

    {{-- Upload Form --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Upload New Images</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('temple-manager.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="images">Select Images (you can select multiple)</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple required>
                    <small class="form-text text-muted">Max file size: 2MB. Allowed types: jpg, png, gif.</small>
                </div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>

    {{-- Existing Images --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3 class="card-title">Current Gallery</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse($images as $image)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="Temple Image">
                            <div class="card-footer text-center">
                                <form action="{{ route('temple-manager.gallery.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col">
                        <p class="text-muted">No images have been uploaded to the gallery yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
