@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid py-4">

        {{-- NEW: Summary Stat Cards --}}
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm border-left-primary h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Bookings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBookings ?? 'N/A' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm border-left-info h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Darshan Bookings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDarshanBookings ?? 'N/A' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-eye fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm border-left-warning h-100">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Seva Bookings</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSevaBookings ?? 'N/A' }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-concierge-bell fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- END NEW --}}

        {{-- NEW: Filter Panel --}}
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
        {{-- END NEW --}}


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
                                    <td colspan="7" class="text-center">No bookings found matching your criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                     {{-- Append query strings to pagination links --}}
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style>
    .card.border-left-primary { border-left: .25rem solid #4e73df !important; }
    .card.border-left-info { border-left: .25rem solid #36b9cc !important; }
    .card.border-left-warning { border-left: .25rem solid #f6c23e !important; }
    .text-gray-300 { color: #dddfeb !important; }
    .text-gray-800 { color: #5a5c69 !important; }
</style>
@endpush