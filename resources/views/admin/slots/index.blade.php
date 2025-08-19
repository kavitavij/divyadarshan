@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h3>Existing Slots for {{ $temple->name }}</h3></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time Slot</th>
                                <th>Capacity (Booked/Total)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($slots as $slot)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($slot->slot_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                <td>{{ $slot->booked_capacity }} / {{ $slot->total_capacity }}</td>
                                <td>
                                    <form action="{{ route('admin.slots.destroy', $slot->id) }}" method="POST">
                                        <a href="{{ route('admin.slots.edit', $slot->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slot?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center">No slots created yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-3">{{ $slots->links() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h3>Add New Slot</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.temples.slots.store', $temple) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Date</label>
                            <input type="date" name="slot_date" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Total Capacity (People)</label>
                            <input type="number" name="total_capacity" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Slot</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
