@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Manage Cab Bookings</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Bookings</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Pickup</th>
                        <th>Drop-off</th>
                        <th>Date & Time</th>
                        <th>Cab Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- This will be empty for now. We will add the controller logic next. --}}
                    {{-- @forelse ($bookings as $booking)
                        <tr>
                            <td>{{ $booking->id }}</td>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->phone }}</td>
                            <td>{{ $booking->pickup_location }}</td>
                            <td>{{ $booking->dropoff_location }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($booking->pickup_time)->format('h:i A') }}</td>
                            <td>{{ $booking->cab_type }}</td>
                            <td>
                                <span class="badge {{ $booking->status === 'Confirmed' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No cab bookings found.</td>
                        </tr>
                    @endforelse --}}
                    <tr>
                        <td colspan="9" class="text-center">No cab bookings found yet. (Controller logic to fetch data is the next step).</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
