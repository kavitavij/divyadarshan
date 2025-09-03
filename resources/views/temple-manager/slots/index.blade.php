@extends('layouts.temple-manager')
 {{-- Or your temple manager layout file --}}

@section('title', 'Manage Darshan Slots')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Darshan Slots for {{ $temple->name }}</h1>
        <div class="dropdown">
    <button class="btn btn-primary dropdown-toggle" type="button" id="addSlotDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-plus fa-sm text-white-50"></i> Add Slots
    </button>
    <div class="dropdown-menu" aria-labelledby="addSlotDropdown">
        <a class="dropdown-item" href="{{ route('temple-manager.slots.settings') }}">
    <i class="fas fa-fw fa-cog"></i> Manage Settings & Days
</a>
        <a class="dropdown-item" href="{{ route('temple-manager.slots.create') }}">
            <i class="fas fa-fw fa-clock"></i> Add Custom Slot
        </a>
    </div>
</div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Upcoming Slots</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Total Capacity</th>
                            <th>Booked</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slots as $slot)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($slot->slot_date)->format('d M, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</td>
                                <td>{{ $slot->total_capacity }}</td>
                                <td>{{ $slot->booked_capacity }}</td>
                                <td>
                                    <span class="badge badge-success">{{ $slot->total_capacity - $slot->booked_capacity }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('temple-manager.slots.edit', $slot) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('temple-manager.slots.destroy', $slot) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slot?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No upcoming slots found. Please create one.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $slots->links() }}
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
