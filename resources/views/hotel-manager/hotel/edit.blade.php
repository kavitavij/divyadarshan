@extends('layouts.hotel-manager')

@section('content')
    <style>
        /* Container */
        .edit-hotel-container {
            background: #f9f9ff;
            min-height: 100vh;
        }

        /* Header */
        .edit-hotel-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .edit-hotel-lead {
            font-size: 1.1rem;
            color: #555;
        }

        /* Card */
        .edit-hotel-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease;
        }

        .edit-hotel-card:hover {
            transform: translateY(-3px);
        }

        /* Labels */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Inputs */
        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ddd;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 0 0.2rem rgba(75, 108, 183, 0.25);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 500;
            transition: transform 0.2s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        /* Image Preview */
        .current-image {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 4px;
            background: #fff;
        }
    </style>

    <div class="container py-5 edit-hotel-container">

        {{-- Header --}}
        <div class="mb-4">
            <h1 class="edit-hotel-title text-primary">✏️ Edit Hotel Details</h1>
            <p class="edit-hotel-lead">
                Update the information for <strong>{{ $hotel->name }}</strong>.
            </p>
        </div>

        {{-- Card --}}
        <div class="card edit-hotel-card">
            <div class="card-body">
                <form action="{{ route('hotel-manager.hotel.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Hotel Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Hotel Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}"
                            required>
                    </div>

                    {{-- Location --}}
                    <div class="mb-3">
                        <label for="location" class="form-label">Location / City</label>
                        <input type="text" name="location" class="form-control"
                            value="{{ old('location', $hotel->location) }}" required>
                    </div>

                    {{-- Temple Association --}}
                    <div class="mb-3">
                        <label for="temple_id" class="form-label">Associated Temple (Optional)</label>
                        <select name="temple_id" class="form-select">
                            <option value="">None</option>
                            @foreach ($temples as $temple)
                                <option value="{{ $temple->id }}" @if ($hotel->temple_id == $temple->id) selected @endif>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $hotel->description) }}</textarea>
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div class="mb-2">
                            @if ($hotel->image)
                                <img src="{{ asset($hotel->image) }}" height="100" class="current-image shadow-sm"
                                    alt="{{ $hotel->name }}">
                            @else
                                <p class="text-muted">No image uploaded.</p>
                            @endif
                        </div>
                        <label for="image" class="form-label">Upload New Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            💾 Update Details
                        </button>
                        <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-outline-secondary">
                            ⬅️ Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
