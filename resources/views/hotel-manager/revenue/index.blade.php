@extends('layouts.hotel-manager')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i>Hotel Revenue</h4>
        </div>

        {{-- Date Filter --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('hotel-manager.revenue.index') }}" method="GET"
                    class="d-flex flex-wrap gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="form-control">
                    </div>
                    <div class="flex-grow-1">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('hotel-manager.revenue.index') }}" class="btn btn-outline-secondary">Clear</a>
                </form>
            </div>
        </div>

        {{-- Revenue Summary --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Total Revenue</h6>
                        <p class="card-text fs-4 fw-bold">₹{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Total Bookings</h6>
                        <p class="card-text fs-4 fw-bold">{{ $totalBookings }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h6 class="card-subtitle mb-2 text-muted">Avg. Booking Value</h6>
                        <p class="card-text fs-4 fw-bold">₹{{ number_format($averageBookingValue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Completed Bookings Table --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Completed Bookings in Period</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Hotel</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>
                                    <strong>{{ $booking->order->order_number ?? 'N/A' }}</strong>
                                </td>
                                <td>{{ $booking->user->name }}</td>
                                <td>{{ $booking->hotel->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, Y') }}</td>
                                <td>
                                    @if ($booking->check_out_date)
                                        {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M, Y') }}
                                    @else
                                        <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">₹{{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center py-4">
                                    No completed bookings found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
            <div class="card-footer">
                {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
