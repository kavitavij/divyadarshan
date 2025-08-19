 @extends('layouts.hotel-manager')

@section('content')
<div class="container-fluid">
    <h1>Add New Room to {{ $hotel->name }}</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hotel-manager.rooms.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>Room Type (e.g., Standard, Deluxe)</label>
                    <input type="text" name="type" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Capacity (people)</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Price per Night (₹)</label>
                        <input type="number" name="price_per_night" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label>Total Number of Rooms</label>
                        <input type="number" name="total_rooms" class="form-control" required>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Room</button>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
