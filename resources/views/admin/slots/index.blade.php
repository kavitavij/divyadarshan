@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Darshan Slots for: {{ $temple->name }}</h3>
        </div>
        <div class="card-body">
            <h4>Add New Slot</h4>
            <form action="{{ route('admin.temples.slots.store', $temple) }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Date</label>
                        <input type="date" name="slot_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Start Time</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>End Time</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Total Capacity</label>
                        <input type="number" name="total_capacity" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Slot</button>
            </form>
            <hr>
            <h4>Existing Slots</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time Slot</th>
                        <th>Booked / Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temple->darshanSlots()->orderBy('slot_date')->get() as $slot)
                    <tr>
                        <td>{{ $slot->slot_date->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                        <td>{{ $slot->booked_capacity }} / {{ $slot->total_capacity }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">No slots created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
