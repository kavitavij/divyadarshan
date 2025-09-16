@extends('layouts.admin')

@section('title', 'Manage All Darshan Slots')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-black-800">Manage All Darshan Slots</h1>
        <a href="{{ route('admin.slots.create') }}" class="btn btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Create New Slot</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.slots.index') }}">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-4">
                        <label for="temple_id">Filter by Temple</label>
                        <select name="temple_id" id="temple_id" class="form-control">
                            <option value="">All Temples</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}" {{ $filterTemple == $temple->id ? 'selected' : '' }}>{{ $temple->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="date">Filter by Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ $filterDate }}">
                    </div>
                    <div class="form-group col-md-4">
                        <button type="submit" class="btn btn-success">Filter</button>
                        <a href="{{ route('admin.slots.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Temple</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Capacity</th>
                            <th>Booked</th>
                            <th>Available</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($slots as $slot)
                            <tr>
                                <td>{{ $slot->temple->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->slot_date)->format('d M, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                                <td>{{ $slot->total_capacity }}</td>
                                <td>{{ $slot->booked_capacity }}</td>
                                <td><span class="badge badge-dark text-black">{{ $slot->total_capacity - $slot->booked_capacity }}</span></td>
                                <td>
                                    <a href="{{ route('admin.slots.edit', $slot) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.slots.destroy', $slot) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">No slots found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $slots->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
