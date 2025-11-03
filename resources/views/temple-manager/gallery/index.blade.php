@extends('layouts.temple-manager')

@section('content')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
        }

        .gallery-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: none;
            border-radius: 0.5rem;
        }

        .gallery-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .gallery-card .card-footer {
            background-color: rgba(0, 0, 0, 0.01);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
    <div class="container-fluid">
        <div class="page-header">
            <div>
                <h1 class="page-title">Manage Temple Gallery</h1>
                <p class="text-muted mb-0">Upload new images or remove existing ones for
                    <strong>{{ $temple->name }}</strong>.
                </p>
            </div>
            <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-outline-secondary back-button">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        {{-- Upload Form --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">Upload New Images</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('temple-manager.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="images" class="form-label">Select Images (you can select multiple)</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple required>
                        <small class="form-text text-muted">Max file size: 2MB. Allowed types: jpg, png, gif.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
                </form>
            </div>
        </div>

        {{-- Existing Images --}}
        <div class="card shadow-sm mt-4">
            <div class="card-header">
                <h5 class="mb-0">Current Gallery</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @forelse($images as $image)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card gallery-card shadow-sm">
                                <img src="{{ asset('storage/' . $image->path) }}" class="card-img-top"
                                    style="height: 200px; object-fit: cover;" alt="Temple Image">
                                <div class="card-footer text-center">
                                    <form action="{{ route('temple-manager.gallery.destroy', $image->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this image?');">
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
    @endsection
