@extends('layouts.admin')

@section('content')
<div class="container-fluid px-6 py-8">
    <h2 class="mb-3 text-3xl font-bold text-gray-800">Cancelled Bookings & Refund Requests</h2>
    <p class="text-muted mb-4">Review all bookings that have been cancelled by users and their refund status.</p>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.booking-cancel.index') }}" class="row g-3 align-items-end">
                {{-- Booking Type --}}
                <div class="col-md-3">
                    <label for="type" class="form-label">Booking Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">All</option>
                        <option value="Darshan" {{ request('type') == 'Darshan' ? 'selected' : '' }}>Darshan</option>
                        <option value="Seva" {{ request('type') == 'Seva' ? 'selected' : '' }}>Seva</option>
                        <option value="Accommodation" {{ request('type') == 'Accommodation' ? 'selected' : '' }}>Accommodation</option>
                    </select>
                </div>

                {{-- User --}}
                <div class="col-md-3">
                    <label for="user" class="form-label">User</label>
                    <input type="text" name="user" id="user" value="{{ request('user') }}" class="form-control" placeholder="User Name">
                </div>

                {{-- Submit --}}
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ route('admin.booking-cancel.index') }}" class="btn btn-light border">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Bookings Table --}}
    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Type</th>
                        <th>Temple / Hotel</th>
                        <th>User</th>
                        <th>Refund Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>
                                <span class="badge
                                    @if($booking->type === 'Darshan') bg-primary
                                    @elseif($booking->type === 'Seva') bg-info
                                    @else bg-secondary @endif">
                                    {{ $booking->type }}
                                </span>
                            </td>
                            <td>{{ $booking->temple?->name ?? $booking->hotel?->name ?? 'N/A' }}</td>
                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge
                                    @if($booking->refundRequest?->status === 'Successful') bg-success
                                    @elseif($booking->refundRequest?->status === 'Pending') bg-warning text-dark
                                    @else bg-secondary @endif">
                                    {{ $booking->refundRequest?->status ?? 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.booking-cancel.show', $booking->id) }}"
                                   class="btn btn-sm btn-primary">
                                   View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">No cancelled bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Optional inline CSS for badge colors --}}
<style>
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.7em;
        border-radius: 0.35rem;
    }
</style>
@endsection
