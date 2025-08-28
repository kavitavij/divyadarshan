@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1>All Bookings for {{ $temple->name }}</h1>
        <div class="card mt-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Type</th>
                            <th>User Name</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <span class="badge {{ $booking->type === 'Darshan' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $booking->type }}
                                    </span>
                                </td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($booking->type === 'Darshan')
                                        {{ $booking->number_of_people }} Devotees
                                    @else
                                        Seva: {{ $booking->seva->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td><span class="badge bg-success">{{ $booking->status }}</span></td>
                                <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No bookings found for this temple.</td>
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
