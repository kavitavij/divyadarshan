@extends('layouts.hotel-manager')

@section('content')
<style>
    /* Page container */
    .rooms-container {
        background: #f9f9ff;
        min-height: 100vh;
        padding: 20px;
    }

    /* Title */
    .rooms-title {
        font-size: 1.6rem;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Card */
    .rooms-card {
        border-radius: 10px;
        border: 1px solid #ddd;
        background: #fff;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    /* Table */
    .rooms-table {
        border-collapse: separate;
        border-spacing: 0 10px; /* space between rows */
        width: 100%;
    }
    .rooms-table thead th {
        background: #4b6cb7;
        color: #fff;
        padding: 12px;
        text-align: center;
        font-size: 0.95rem;
    }
    .rooms-table tbody tr {
        background: #fff;
        border: 1px solid #eee;
    }
    .rooms-table tbody td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        font-size: 0.9rem;
    }

    /* Buttons */
    .btn-action {
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 6px 12px;
        margin: 0 3px;
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        font-weight: 500;
    }
</style>

<div class="container-fluid rooms-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="rooms-title">Manage Rooms for {{ $hotel->name }}</h1>
        <a href="{{ route('hotel-manager.rooms.create') }}" class="btn btn-primary">
            + Add New Room
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card rooms-card p-3">
        <div class="card-body">
            <table class="rooms-table table">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price / Night</th>
                        <th>Total Rooms</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                    <tr>
                        <td>{{ $room->type }}</td>
                        <td>{{ $room->capacity }} People</td>
                        <td>₹{{ number_format($room->price_per_night, 2) }}</td>
                        <td>{{ $room->total_rooms }}</td>
                        <td>
                            <form action="{{ route('hotel-manager.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                <a href="{{ route('hotel-manager.rooms.edit', $room->id) }}" class="btn btn-sm btn-primary btn-action">
                                    Edit
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this room?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No rooms have been added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
