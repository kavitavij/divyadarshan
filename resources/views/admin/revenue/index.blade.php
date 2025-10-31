@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Platform Revenue</h1>
    </div>

    {{-- Date Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.revenue.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    <a href="{{ route('admin.revenue.download', request()->query()) }}" class="btn btn-success w-100">
                        <i class="fas fa-file-excel me-1"></i> Download
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text fs-2 fw-bold">₹{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6">
            <div class="row g-4">
                <div class="col-lg-3 col-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Darshan</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($darshanRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Hotel Stays</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($stayRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Sevas</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($sevaRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Donations & Ebooks</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($donationRevenue + $ebookRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="revenue-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="darshan-tab" data-bs-toggle="tab" data-bs-target="#darshan"
                        type="button" role="tab" aria-controls="darshan" aria-selected="true">Recent Darshan
                        Bookings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stays-tab" data-bs-toggle="tab" data-bs-target="#stays" type="button"
                        role="tab" aria-controls="stays" aria-selected="false">Recent Hotel Stays</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="donations-tab" data-bs-toggle="tab" data-bs-target="#donations"
                        type="button" role="tab" aria-controls="donations" aria-selected="false">Recent
                        Donations</button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="revenue-tabs-content">
                {{-- Darshan Bookings Table --}}
                <div class="tab-pane fade show active" id="darshan" role="tabpanel" aria-labelledby="darshan-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Devotee</th>
                                    <th>Temple</th>
                                    <th>Darshan Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($darshanBookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->temple_name ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') }}</td>
                                        <td class="text-end">₹{{ number_format($booking->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No darshan bookings found for
                                            this period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $darshanBookings->appends(request()->query())->links() }}</div>
                </div>

                {{-- Hotel Stay Bookings Table --}}
                <div class="tab-pane fade" id="stays" role="tabpanel" aria-labelledby="stays-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Guest</th>
                                    <th>Hotel</th>
                                    <th>Check-in</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($stayBookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->user->name }}</td>
                                        <td>{{ $booking->hotel->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->check_in_date->format('d M, Y') }}</td>
                                        <td class="text-end">₹{{ number_format($booking->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No hotel bookings found for this
                                            period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $stayBookings->appends(request()->query())->links() }}</div>
                </div>

                {{-- Donations Table --}}
                <div class="tab-pane fade" id="donations" role="tabpanel" aria-labelledby="donations-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Purpose</th>
                                    <th>Temple</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($donations as $donation)
                                    <tr>
                                        <td>{{ $donation->donation_purpose ?? 'General Donation' }}</td>
                                        <td>{{ $donation->temple->name ?? 'N/A' }}</td>
                                        <td>{{ $donation->created_at->format('d M, Y') }}</td>
                                        <td class="text-end">₹{{ number_format($donation->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No donations found for this
                                            period.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $donations->appends(request()->query())->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
