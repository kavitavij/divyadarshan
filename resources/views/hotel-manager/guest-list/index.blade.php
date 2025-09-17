@extends('layouts.hotel-manager')

@section('content')
<style>
    .page-title {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #333;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-top: 20px;
        padding: 20px;
        background: #fff;
    }
    .table {
        border-collapse: separate;
        border-spacing: 0 12px; /* adds gaps between rows */
        width: 100%;
    }
    .table thead th {
        background: #f5f5f5;
        padding: 12px;
        font-weight: 600;
        text-align: center;
    }
    .table tbody tr {
        background: #fafafa;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .table td {
        padding: 12px;
        text-align: center;
    }
    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
    }
    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="container-fluid">
    <h1 class="page-title">Guest List for {{ $hotel->name }}</h1>

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Guests</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                <tr>
                    <td><strong>#{{ $booking->id }}</strong></td>
                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                    <td>{{ $booking->room->type ?? 'N/A' }}</td>
                    <td>{{ $booking->number_of_guests }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td>
                    <td>
                        @if($booking->status == 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                        @elseif($booking->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($booking->status == 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-secondary">{{ $booking->status }}</span>
                        @endif
                    </td>
                    <td>
                        @if(in_array($booking->status, ['Confirmed', 'pending']))

                            <form action="{{ route('hotel-manager.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                @csrf
                                <button type="submit" class="btn-cancel">Cancel</button>
                            </form>
                        @elseif($booking->status == 'Cancelled')
                            @if($booking->refund_status == 'pending')
                                <span class="badge bg-warning text-dark">Refund Pending</span>
                            @elseif($booking->refund_status == 'approved')
                                <span class="badge bg-success">Refunded</span>
                            @elseif($booking->refund_status == 'rejected')
                                <span class="badge bg-danger">Refund Rejected</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        @else}
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No guests found for this hotel.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
