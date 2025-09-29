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
        padding: 20px;
        background: #fff;
        margin-bottom: 30px;
    }

    .summary-box {
        padding: 20px;
        border-radius: 10px;
        background: #f8f9fa;
        text-align: center;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .summary-title {
        font-size: 14px;
        color: #6c757d;
    }

    .summary-value {
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }

    .filter-group label {
        font-weight: 500;
        font-size: 14px;
        color: #555;
    }

    .filter-group input[type="date"] {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-filter {
        background-color: #007bff;
        color: white;
        padding: 8px 16px;
        font-size: 14px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-filter:hover {
        background-color: #0056b3;
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .data-table thead th {
        background: #f5f5f5;
        padding: 12px;
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        color: #555;
    }

    .data-table tbody tr {
        background: #fafafa;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .data-table td {
        padding: 12px;
        text-align: center;
        font-size: 14px;
        color: #333;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .filter-group {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="container-fluid">
    <h1 class="page-title">Hotel Revenue</h1>

    {{-- Date Filter --}}
    <div class="card">
        <form action="{{ route('hotel-manager.revenue.index') }}" method="GET">
            <div class="filter-group">
                <div>
                    <label for="start_date">Start Date</label><br>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}">
                </div>

                <div>
                    <label for="end_date">End Date</label><br>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}">
                </div>

                <div>
                    <button type="submit" class="btn-filter">Filter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Revenue Summary --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="summary-box">
                <div class="summary-title">Total Revenue</div>
                <div class="summary-value">₹{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="summary-box">
                <div class="summary-title">Total Bookings</div>
                <div class="summary-value">{{ $totalBookings }}</div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="summary-box">
                <div class="summary-title">Avg. Booking Value</div>
                <div class="summary-value">₹{{ number_format($averageBookingValue, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Completed Bookings Table --}}
    <div class="card">
        <h2 class="mb-3" style="font-size: 20px; font-weight: 600;">Completed Bookings in Period</h2>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Guest</th>
                        <th>Hotel</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>{{ $booking->user->name }}</td>
                            <td>{{ $booking->hotel->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date ,)->format('d M, Y') }}</td>
                            <td>
                                @if($booking->check_out_date)
                                    {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}
                                @else
                                    <span class="text-warning">Pending</span>
                                @endif
                            </td>
                            <td>₹{{ number_format($booking->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center py-3">
                                No completed bookings found for this period.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
