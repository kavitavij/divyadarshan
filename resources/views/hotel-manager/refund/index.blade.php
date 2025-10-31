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

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        margin-top: 20px;
        padding: 20px;
        background: #fff;
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 12px;
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

    .btn-view {
        background-color: #007bff;
        color: white;
        padding: 6px 14px;
        font-size: 13px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
    }

    .btn-view:hover {
        background-color: #0056b3;
    }
</style>

<div class="container-fluid">
    <h1 class="page-title">Refund & Cancellation Requests</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Guest Name</th>
                    <th>Dates</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($refundRequests as $booking)
                    <tr>
                    <td><strong>{{ $booking->order->order_number ?? 'N/A' }}</strong></td>
                    <td>{{ $booking->user->name }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, Y') }} - 
                            {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}
                        </td>
                        <td>₹{{ number_format($booking->total_amount, 2) }}</td>
                        <td>
                            @switch($booking->refund_status)
                                @case('approved')
                                    <span class="badge bg-success">Approved</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    @break
                                @default
                                    <span class="badge bg-warning text-dark">Pending</span>
                            @endswitch
                        </td>
                        <td>
                            <a href="{{ route('hotel-manager.refund.show', $booking->id) }}" class="btn-view">
                                View Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No pending refund requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $refundRequests->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
