@extends('layouts.temple-manager')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Temple Revenue</h1>
            <h5 class="text-muted">{{ $temple->name }}</h5>
        </div>
    </div>

    {{-- Date Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('temple-manager.revenue.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="form-control">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" class="form-control">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                    {{-- Assuming a download route exists, similar to the admin one --}}
                    <a href="{{ route('temple-manager.revenue.download', request()->query()) }}"
                        class="btn btn-success w-100">
                        <i class="fas fa-file-excel me-1"></i> Download
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Revenue Summary Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-4 col-md-12">
            <div class="card text-white bg-primary h-100">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text fs-2 fw-bold">₹{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="row g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Darshan Revenue</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($darshanRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Seva Revenue</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($sevaRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h6 class="card-subtitle mb-2 text-muted">Donation Revenue</h6>
                            <p class="card-text fs-5 fw-bold">₹{{ number_format($donationRevenue, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Tables --}}
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="revenue-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="darshan-tab" data-bs-toggle="tab" data-bs-target="#darshan"
                        type="button" role="tab" aria-controls="darshan" aria-selected="true">Recent Darshan
                        Bookings</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="seva-tab" data-bs-toggle="tab" data-bs-target="#seva" type="button"
                        role="tab" aria-controls="seva" aria-selected="false">Recent Seva Bookings</button>
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
                {{-- Darshan Bookings --}}
                <div class="tab-pane fade show active" id="darshan" role="tabpanel" aria-labelledby="darshan-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Date</th>
                                    <th>Devotees</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($darshanBookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>{{ $booking->booking_date->format('d M, Y') }}</td>
                                        <td>{{ $booking->number_of_people }}</td>
                                        <td class="text-end">₹{{ number_format($booking->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No Darshan bookings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $darshanBookings->appends(request()->query())->links() }}</div>
                </div>

                {{-- Seva Bookings --}}
                <div class="tab-pane fade" id="seva" role="tabpanel" aria-labelledby="seva-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Seva Name</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sevaBookings as $booking)
                                    <tr>
                                        <td>{{ $booking->seva->name }}</td>
                                        <td>{{ $booking->created_at->format('d M, Y') }}</td>
                                        <td class="text-end">₹{{ number_format($booking->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No Seva bookings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $sevaBookings->appends(request()->query())->links() }}</div>
                </div>

                {{-- Donations --}}
                <div class="tab-pane fade" id="donations" role="tabpanel" aria-labelledby="donations-tab">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Purpose</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($donations as $donation)
                                    <tr>
                                        <td>{{ $donation->donation_purpose }}</td>
                                        <td>{{ $donation->created_at->format('d M, Y') }}</td>
                                        <td class="text-end">₹{{ number_format($donation->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No donations found.</td>
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
