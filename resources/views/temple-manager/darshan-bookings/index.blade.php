@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1>Darshan Bookings for {{ $temple->name }}</h1>
        <div class="card mt-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>User Name</th>
                            <th>Devotees</th>
                            <th>Status</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>{{ $booking->number_of_people }}</td>
                                <td><span class="badge bg-success">{{ $booking->status }}</span></td>
                                <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Darshan bookings found for this temple.</td>
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
