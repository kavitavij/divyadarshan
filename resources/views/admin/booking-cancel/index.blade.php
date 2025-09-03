@extends('layouts.admin')

@section('title', 'Accommodation Refund Requests')

@section('content')
<div class="container-fluid px-6 py-8">
    <h2 class="mb-3 text-3xl font-bold text-gray-800">Accommodation Refund Requests</h2>
    <p class="text-muted mb-4">Review all cancelled accommodation bookings and process their refunds.</p>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Request ID</th>
                        <th>Booking ID</th>
                        <th>Hotel</th>
                        <th>User</th>
                        <th>Refund Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    @forelse ($refundRequests as $request)
        {{-- Add this @if statement as a safety check --}}
        @if ($request->bookingable)
            <tr>
                <td>#{{ $request->id }}</td>
                <td>#{{ $request->bookingable->id }}</td>
                <td>{{ $request->bookingable->room->hotel->name ?? 'N/A' }}</td>
                <td>{{ $request->bookingable->user->name ?? 'N/A' }}</td>
                <td>
                    <span class="badge {{ $request->status === 'Successful' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $request->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.booking-cancel.stay.show', $request) }}" class="btn btn-sm btn-primary">
                        View Details
                    </a>
                </td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="6" class="text-center text-muted py-5">No pending refund requests found.</td>
        </tr>
    @endforelse
</tbody>
            </table>
            <div class="mt-3 d-flex justify-content-center">
                {{ $refundRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
