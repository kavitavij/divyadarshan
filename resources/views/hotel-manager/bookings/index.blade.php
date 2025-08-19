@extends('layouts.hotel-manager')

@section('content')
<div class="container-fluid">
    <h1>Accommodation Bookings for {{ $hotel->name }}</h1>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User Name</th>
                        <th>Room Type</th>
                        <th>Guests</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->room->type ?? 'N/A' }}</td>
                        <td>{{ $booking->number_of_guests }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td>
                        <td><span class="badge bg-success">{{ $booking->status }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No bookings found for this hotel.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
