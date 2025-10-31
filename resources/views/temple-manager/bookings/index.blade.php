@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid py-4">
        <h1 class="h3 mb-4 text-gray-800">Manage Bookings for {{ $temple->name }}</h1>
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="card summary-card-modern border-0 shadow h-100">
                    <div class="card-body d-flex align-items-center">
                        <!-- <div class="summary-icon bg-gradient-info text-white me-3"><i class="fas fa-eye fa-lg"></i></div> -->
                        <div>
                            <div class="fw-bold text-uppercase small text-info mb-1">Darshan Bookings</div>
                            <div class="fs-3 fw-bold">{{ $darshanBookingsCount ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card summary-card-modern border-0 shadow h-100">
                    <div class="card-body d-flex align-items-center">
                        <!-- <div class="summary-icon bg-gradient-warning text-white me-3"><i class="fas fa-concierge-bell fa-lg"></i></div> -->
                        <div>
                            <div class="fw-bold text-uppercase small text-warning mb-1">Seva Bookings</div>
                            <div class="fs-3 fw-bold">{{ $sevaBookingsCount ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card summary-card-modern border-0 shadow h-100">
                    <div class="card-body d-flex align-items-center">
                        <!-- <div class="summary-icon bg-gradient-primary text-white me-3"><i class="fas fa-calendar fa-lg"></i></div> -->
                        <div>
                            <div class="fw-bold text-uppercase small text-primary mb-1">Total Bookings</div>
                            <div class="fs-3 fw-bold">{{ $totalBookings ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>Filter Bookings
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('temple-manager.bookings.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="type" class="form-label">Booking Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">All Types</option>
                            <option value="darshan" {{ request('type') == 'darshan' ? 'selected' : '' }}>Darshan</option>
                            <option value="seva" {{ request('type') == 'seva' ? 'selected' : '' }}>Seva</option>
                        </select>
                    </div>
                    <div class="col-md-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                        <a href="{{ route('temple-manager.bookings.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h4 class="mb-0">All Bookings for {{ $temple->name }}</h4>
                <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-modern">
                        <thead class="table-dark">
                            <tr>
                                <th>Booking ID</th>
                                <th>Order ID</th>
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
                                <td><strong>#{{ $booking->id }}</strong></td>
                                <td>@if($booking->type === 'Seva')
                                            {{ $booking->order->order_number ?? 'N/A' }}
                                        @elseif($booking->type === 'Darshan')
                                            {{ $booking->order->order_number ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill px-3 py-2 {{ $booking->type === 'Darshan' ? 'bg-info' : 'bg-warning text-dark' }}">
                                            <i class="fas {{ $booking->type === 'Darshan' ? 'fa-eye' : 'fa-concierge-bell' }} me-1"></i> {{ $booking->type }}
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
                                    <td><span class="badge bg-success text-capitalize px-3 py-2">{{ $booking->status }}</span></td>
                                    <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('temple-manager.bookings.show', ['type' => strtolower($booking->type), 'id' => $booking->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No bookings found matching your criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bookings->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style>
    .summary-card-modern {
        background: linear-gradient(135deg, #f8fafc 60%, #e3e9f7 100%);
        border-radius: 1rem;
        transition: box-shadow 0.2s;
    }
    .summary-card-modern:hover {
        box-shadow: 0 0 0 0.2rem #b6c6e6;
    }
    .summary-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 60%, #224abe 100%) !important;
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 60%, #1e7fa6 100%) !important;
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 60%, #e5a100 100%) !important;
    }
    .table-modern th, .table-modern td {
        vertical-align: middle;
        font-size: 1rem;
    }
    .table-modern thead {
        background: #e3e9f7;
        color: #2d3a4b;
    }
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }
</style>
@endpush
