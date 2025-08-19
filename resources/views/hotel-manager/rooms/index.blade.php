 @extends('layouts.hotel-manager')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Rooms for {{ $hotel->name }}</h1>
        <a href="{{ route('hotel-manager.rooms.create') }}" class="btn btn-primary">Add New Room</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price/Night</th>
                        <th>Total Rooms</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                    <tr>
                        <td>{{ $room->type }}</td>
                        <td>{{ $room->capacity }} people</td>
                        <td>₹{{ number_format($room->price_per_night, 2) }}</td>
                        <td>{{ $room->total_rooms }}</td>
                        <td>
                            <form action="{{ route('hotel-manager.rooms.destroy', $room->id) }}" method="POST">
                                <a href="{{ route('hotel-manager.rooms.edit', $room->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No rooms have been added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
