@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="mb-0">All Bookings for {{ $temple->name }}</h4>
                 <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Booking ID</th>
                                <th>Type</th>
                                <th>User Name</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Booked On</th>
                                <th>Actions</th>
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
                                            {{ $booking->number_of_people }} Devotees on {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}
                                        @else
                                            Seva: {{ $booking->seva->name ?? 'N/A' }}
                                        @endif
                                    </td>
                                    <td><span class="badge bg-success text-capitalize">{{ $booking->status }}</span></td>
                                    <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('temple-manager.bookings.show', ['type' => strtolower($booking->type), 'id' => $booking->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No bookings found for this temple.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
