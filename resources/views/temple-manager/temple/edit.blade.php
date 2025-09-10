@extends('layouts.temple-manager') {{-- We can reuse the admin layout for a consistent feel --}}

@section('content')
    <div class="container-fluid">
        <h1>Edit Your Temple Details</h1>
        <p>Update the information for <strong>{{ $temple->name }}</strong>.</p>

        <div class="card mt-4">
            <div class="card-body">
                <form action="{{ route('temple-manager.temple.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="name">Temple Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $temple->name) }}"
                            required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="location">Location / City</label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $temple->location) }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="description">Short Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $temple->description) }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="image">Current Image</label>
                        <div>
                            @if ($temple->image)
                                <img src="{{ asset($temple->image) }}" height="100" class="mb-2 rounded"
                                    alt="{{ $temple->name }}">
                            @endif
                        </div>
                        <label for="image">Upload New Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <hr>
                    <h4 class="mt-4">Page Content Sections</h4>
                    <div class="card-body">
                    <div class="mb-3">
                        <label for="about" class="form-label">About Section</label>
                        <textarea class="form-control wysiwyg-editor" name="about">{{ old('about', $temple->about) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="online_services" class="form-label">Online Services Section</label>
                        <textarea class="form-control wysiwyg-editor" name="online_services">{{ old('online_services', $temple->online_services) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="social_services" class="form-label">Social Services Section</label>
                        <textarea class="form-control wysiwyg-editor" name="social_services">{{ old('social_services', $temple->social_services) }}</textarea>
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary">Update Details</button>
                    <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
