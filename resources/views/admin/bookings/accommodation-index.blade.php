@extends('layouts.admin')

@section('title', 'Accommodation Bookings')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">Accommodation Bookings</h1>
    <p class="text-muted">A list of all hotel and room bookings.</p>

    {{-- Filter Form --}}
    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.bookings.accommodation') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="hotel_id" class="form-label">Filter by Hotel</label>
                    <select name="hotel_id" id="hotel_id" class="form-select">
                        <option value="">All Hotels</option>
                        @foreach ($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ $filterHotel == $hotel->id ? 'selected' : '' }}>
                                {{ $hotel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date" class="form-label">Filter by Date</label>
                    <input type="date" name="date" id="date" value="{{ $filterDate }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="{{ route('admin.bookings.accommodation') }}" class="btn btn-light border">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>Booking ID</th>
            <th>Hotel</th>
            <th>User</th>
            <th>Check-in</th>
            <th>Booking Status</th>
            {{-- <th>Refund Status</th> <-- NEW COLUMN --}}
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bookings as $booking)
            <tr>
                <td>{{ $booking->id }}</td>
                <td>{{ $booking->room->hotel->name ?? 'N/A' }}</td>
                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M, Y') }}</td>
                <td>
                    <span class="badge {{ strtolower($booking->status) === 'confirmed' ? 'bg-success' : (strtolower($booking->status) === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ Str::ucfirst($booking->status) }}
                    </span>
                </td>
                {{-- <td>
                    {{-- NEW COLUMN DATA --}}
                    {{-- @if($booking->refund_status)
                        <span class="badge {{ $booking->refund_status === 'Successful' ? 'bg-success' : 'bg-warning text-dark' }}">
                            {{ $booking->refund_status }}
                        </span>
                    @else
                        <span class="badge bg-secondary">N/A</span>
                    @endif
                </td>  --}}
                <td>
                    <a href="{{ route('admin.bookings.accommodation.show', $booking->id) }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted">No accommodation bookings found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
