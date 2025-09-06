@extends('layouts.hotel-manager')

@section('content')
<style>
.gallery-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

.gallery-card {
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, box-shadow 0.2s;
}

.gallery-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

.gallery-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.gallery-card .card-body {
    padding: 10px;
    text-align: center;
}

.gallery-card .btn {
    font-size: 13px;
    padding: 5px 10px;
    margin-top: 5px;
}
</style>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Image Gallery for {{ $hotel->name }}</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upload New Images</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('hotel-manager.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="images">Select Images (you can select multiple)</label>
                    <input type="file" name="images[]" class="form-control-file" id="images" multiple required>
                    <small class="form-text text-muted">Max file size: 2MB. Allowed types: jpg, png, gif.</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Upload Images</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Current Gallery</h6>
        </div>
        <div class="card-body">
            @if($hotel->images->isEmpty())
                <p>No gallery images have been uploaded yet.</p>
            @else
                <div class="gallery-container">
                    @foreach($hotel->images as $image)
                        <div class="gallery-card">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Gallery Image">
                            <div class="card-body">
                                <form action="{{ route('hotel-manager.gallery.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
