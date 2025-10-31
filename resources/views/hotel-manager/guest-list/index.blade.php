@extends('layouts.hotel-manager')

@section('content')
    <style>
        .page-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
            display: inline-block;
        }

        .back-button {
            background-color: #6f4f28;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            /* Use inline-flex for alignment */
            align-items: center;
            /* Vertically center icon and text */
            margin-left: 20px;
            vertical-align: middle;
            /* Align with title */
        }

        .back-button:hover {
            background-color: #5a3f1e;
            color: white;
        }

        .card {
            border: none;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table thead th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 600;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
        }

        .table td {
            padding: 15px;
            text-align: center;
            vertical-align: middle;
        }

        .badge {
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .btn-cancel {
            background: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
        }

        .pagination {
            display: flex;
            justify-content: center;
            padding: 10px;
            list-style: none;
        }

        .page-item {
            margin: 0 3px;
        }

        .page-link {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
            color: #6f4f28;
            text-decoration: none;
            font-size: 14px;
        }

        .page-link:hover {
            background-color: #f8f9fa;
        }

        .page-item.active .page-link {
            background-color: #6f4f28;
            color: #fff;
            border-color: #6f4f28;
        }
    </style>

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Guest List for {{ $hotel->name }}</h1>
            <a href="{{ route('hotel-manager.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Order ID</th>
                                <th>Guest Contact</th>
                                <th>Room</th>
                                <th>Guests</th>
                                <th>Check in/out</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td><strong>#{{ $booking->id }}</strong></td>
                                    <td><strong>{{ $booking->order->order_number ?? 'N/A' }}</strong></td>
                                    <td class="text-start">
                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i> {{ $booking->email ?? 'N/A' }}
                                        </small><br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i> {{ $booking->phone_number ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>{{ $booking->room->type ?? 'N/A' }}</td>
                                    <td>{{ $booking->number_of_guests }}</td>

                                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M') }} -
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}</td>

                                    <td>
                                        @if (strtolower($booking->status) == 'confirmed')
                                            <span class="badge bg-success">Confirmed</span>
                                        @elseif(strtolower($booking->status) == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif(strtolower($booking->status) == 'cancelled')
                                            <span class="badge bg-danger">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($booking->payment_method == 'pay_at_hotel')
                                            <span class="badge bg-info text-dark">Pay at Hotel</span>
                                        @else
                                            <span class="badge bg-primary">Paid Online</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array(strtolower($booking->status), ['confirmed', 'pending']))
                                            <form action="{{ route('hotel-manager.bookings.cancel', $booking->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this booking? This will notify the user.');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Cancel</button>
                                            </form>
                                        @elseif($booking->status == 'Cancelled' && $booking->refund_status)
                                            <span class="badge bg-secondary">{{ ucfirst($booking->refund_status) }}</span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">No guests found for this hotel.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                @if ($bookings->hasPages())
                    <div class="card-footer bg-white">
                        {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
