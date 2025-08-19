@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Time Slots for {{ $temple->name }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <!-- Date Selector -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.temples.slots.index', $temple) }}" method="GET">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Select a Date to Manage Slots</label>
                                <input type="date" name="date" id="date" class="form-control" value="{{ $selectedDate }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">View Slots</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Existing Slots for Selected Date -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Slots for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <th>Capacity (Booked/Total)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($slots as $slot)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                <td>{{ $slot->booked_capacity }} / {{ $slot->total_capacity }}</td>
                                <td>
                                    <form action="{{ route('admin.slots.destroy', $slot->id) }}" method="POST">
                                        <a href="{{ route('admin.slots.edit', $slot->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center">No slots created for this date yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add New Slot Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3>Add New Slot for this Date</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.temples.slots.store', $temple) }}" method="POST">
                        @csrf
                        <input type="hidden" name="slot_date" value="{{ $selectedDate }}">
                        <div class="form-group mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Total Capacity</label>
                            <input type="number" name="total_capacity" class="form-control" value="1000" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Slot</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
