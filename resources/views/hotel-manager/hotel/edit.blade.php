 @extends('layouts.hotel-manager')
@section('content')
<div class="container-fluid">
    <h1>Edit Your Hotel Details</h1>
    <p>Update the information for <strong>{{ $hotel->name }}</strong>.</p>

    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('hotel-manager.hotel.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="name">Hotel Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $hotel->name) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="location">Location / City</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $hotel->location) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="temple_id">Associated Temple (Optional)</label>
                    <select name="temple_id" class="form-control">
                        <option value="">None</option>
                        @foreach($temples as $temple)
                            <option value="{{ $temple->id }}" @if($hotel->temple_id == $temple->id) selected @endif>
                                {{ $temple->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $hotel->description) }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="image">Current Image</label>
                    <div>
                        @if($hotel->image)
                            <img src="{{ asset($hotel->image) }}" height="100" class="mb-2 rounded" alt="{{ $hotel->name }}">
                        @endif
                    </div>
                    <label for="image">Upload New Image (optional)</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Update Details</button>
                <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
