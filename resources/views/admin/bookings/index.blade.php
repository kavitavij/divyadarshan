@extends('layouts.admin')

@section('title', 'Temple Bookings')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-3">Temple Bookings</h1>
        {{-- Updated description --}}
        <p class="text-muted">Combined list of all Darshan and Seva bookings, sorted by the most recent.</p>

        {{-- Filter Form --}}
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
                    {{-- Booking Type --}}
                    <div class="col-md-3">
                        <label for="type" class="form-label">Booking Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All Temple Bookings</option>
                            <option value="Darshan" {{ request('type') == 'Darshan' ? 'selected' : '' }}>Darshan</option>
                            <option value="Seva" {{ request('type') == 'Seva' ? 'selected' : '' }}>Seva</option>
                            {{-- Accommodation option removed --}}
                        </select>
                    </div>

                    {{-- Date --}}
                    <div class="col-md-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" name="date" id="date" value="{{ request('date') }}"
                            class="form-control">
                    </div>

                    {{-- Submit --}}
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-light border">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Bookings Table --}}
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Booking ID</th>
                            <th>Type</th>
                            <th>Temple</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <span class="badge {{ $booking->type === 'Darshan' ? 'bg-primary' : 'bg-info' }}">
                                        {{ $booking->type }}
                                    </span>
                                </td>
                                <td>{{ $booking->location_name ?? 'N/A' }}</td>
                                <td>{{ $booking->user_name ?? 'N/A' }}</td>
                                <td>
                                    <span
                                        class="badge {{ strtolower($booking->status) === 'confirmed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ Str::ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</td>
                                <td>
                                    {{-- The link to the generic show page remains correct --}}
                                    <a href="{{ route('admin.bookings.show', ['type' => $booking->type, 'id' => $booking->id]) }}"
                                        class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No bookings found for the selected
                                    filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-center">
                    {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
