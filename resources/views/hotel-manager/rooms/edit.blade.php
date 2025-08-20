@extends('layouts.hotel-manager')

@section('content')
<style>
    .page-title {
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #2c3e50;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: none;
    }
    .card-body {
        padding: 30px;
    }
    label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #34495e;
    }
    .form-control {
        border-radius: 8px;
        padding: 10px 12px;
        border: 1px solid #ccc;
        transition: all 0.2s ease-in-out;
    }
    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 6px rgba(52,152,219,0.3);
    }
    .btn-primary {
        background: #3498db;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        transition: 0.2s ease-in-out;
    }
    .btn-primary:hover {
        background: #2980b9;
    }
    .btn-secondary {
        border-radius: 8px;
        padding: 10px 20px;
        margin-left: 10px;
    }
    textarea {
        resize: none;
    }
</style>

<div class="container-fluid">
    <h1 class="page-title">Edit Room: {{ $room->type }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hotel-manager.rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')

                
                <div class="form-group mb-3">
                    <label>Room Type</label>
                    <select name="type" class="form-control" required>
                        <option value="">-- Select Room Type --</option>
                        <option value="Single" {{ old('type', $room->type) == 'Single' ? 'selected' : '' }}>Single</option>
                        <option value="Double" {{ old('type', $room->type) == 'Double' ? 'selected' : '' }}>Double</option>
                        <option value="Deluxe" {{ old('type', $room->type) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="Suite" {{ old('type', $room->type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                        <option value="Family Room" {{ old('type', $room->type) == 'Family Room' ? 'selected' : '' }}>Family Room</option>
                        <option value="Luxury" {{ old('type', $room->type) == 'Luxury' ? 'selected' : '' }}>Luxury</option>
                        <option value="Dormitory" {{ old('type', $room->type) == 'Dormitory' ? 'selected' : '' }}>Dormitory</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Capacity (people)</label>
                        <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Price per Night (₹)</label>
                        <input type="number" name="price_per_night" class="form-control" step="0.01" value="{{ old('price_per_night', $room->price_per_night) }}" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Total Number of Rooms</label>
                        <input type="number" name="total_rooms" class="form-control" value="{{ old('total_rooms', $room->total_rooms) }}" required>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $room->description) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Room</button>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
