@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Rooms for {{ $hotel->name }}</h1>
        <a href="{{ route('admin.hotels.rooms.create', $hotel) }}" class="btn btn-primary">Add New Room</a>
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
                        <th>Price per Night</th>
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
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-primary">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No rooms have been added for this hotel yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
