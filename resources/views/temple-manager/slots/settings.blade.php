@extends('layouts.temple-manager')

@section('title', 'Slot Settings')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Darshan Slot Settings</h1>
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <!-- Manage Default Slots -->
    <div class="card shadow mb-4">
        <div class="card-header">Manage Default Slots</div>
        <div class="card-body">
            <p>These are the default times and capacities that will appear every day unless you override them.</p>
            <form action="{{ route('temple-manager.slots.settings.update') }}" method="POST">
                @csrf
                @forelse($defaultSlots as $slot)
                <div class="row align-items-center mb-3 border-bottom pb-3">
                    <div class="col-md-3"><input type="time" name="slots[{{$slot->id}}][start_time]" value="{{ $slot->start_time }}" class="form-control" required></div>
                    <div class="col-md-3"><input type="time" name="slots[{{$slot->id}}][end_time]" value="{{ $slot->end_time }}" class="form-control" required></div>
                    <div class="col-md-3"><input type="number" name="slots[{{$slot->id}}][capacity]" value="{{ $slot->capacity }}" class="form-control" placeholder="Capacity" required></div>
                    <div class="col-md-3 form-check form-switch"><input class="form-check-input" type="checkbox" name="slots[{{$slot->id}}][is_active]" value="1" @if($slot->is_active) checked @endif> <label>Active</label></div>
                </div>
                @empty
                <p class="text-danger">No default slots configured. Please contact admin.</p>
                @endforelse
                <button type="submit" class="btn btn-primary">Save Default Settings</button>
            </form>
        </div>
    </div>

    <!-- Close Bookings for a Specific Day -->
    <div class="card shadow mb-4">
        <div class="card-header">Close/Open Bookings for a Specific Day</div>
        <div class="card-body">
            <form action="{{ route('temple-manager.slots.day-status.update') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-3 form-group"><label>Date</label><input type="date" name="date" class="form-control" required></div>
                    <div class="col-md-3 form-group"><label>Action</label><select name="action" class="form-select"><option value="close">Close Bookings</option><option value="open">Re-Open Bookings</option></select></div>
                    <div class="col-md-4 form-group"><label>Reason for Closing (Optional)</label><input type="text" name="reason" class="form-control" placeholder="e.g., Temple closed for Bhadra"></div>
                    <div class="col-md-2 align-self-end"><button type="submit" class="btn btn-warning">Update Day Status</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Booking Day Status List -->
<div class="card shadow mb-4">
    <div class="card-header">Booking Day Status List</div>
    <div class="card-body">
        @if($dayStatuses->isEmpty())
            <p class="text-muted">No date-based booking statuses have been set yet.</p>
        @else
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dayStatuses as $status)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($status->date)->format('d M, Y') }}</td>
                            <td>
                                @if($status->is_closed)
                                    <span class="badge bg-danger">Closed</span>
                                @else
                                    <span class="badge bg-success">Open</span>
                                @endif
                            </td>
                            <td>{{ $status->reason ?? '-' }}</td>
                            <td>
                                <form method="POST" action="{{ route('temple-manager.slots.day-status.delete', $status->id) }}" onsubmit="return confirm('Are you sure you want to delete this status?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@endsection
