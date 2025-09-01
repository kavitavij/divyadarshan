@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-3">All Bookings</h1>
        <p class="text-muted">Combined list of all Darshan, Seva, and Accommodation bookings across the platform, sorted by
            the most recent.</p>

        {{-- Filter Form --}}
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3 align-items-end">
                    {{-- Booking Type --}}
                    <div class="col-md-3">
                        <label for="type" class="form-label">Booking Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="">All</option>
                            <option value="Darshan" {{ request('type') == 'Darshan' ? 'selected' : '' }}>Darshan</option>
                            <option value="Seva" {{ request('type') == 'Seva' ? 'selected' : '' }}>Seva</option>
                            <option value="Accommodation" {{ request('type') == 'Accommodation' ? 'selected' : '' }}>
                                Accommodation</option>
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
                            <th>Temple / Hotel</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    <span class="badge
                                        @if($booking->type === 'Darshan') bg-primary
                                        @elseif($booking->type === 'Seva') bg-info
                                        @else bg-secondary @endif">
                                        {{ $booking->type }}
                                    </span>
                                </td>
                                <td>
                                    @if ($booking->type === 'Darshan')
                                        {{ $booking->temple->name ?? 'N/A' }}
                                    @elseif($booking->type === 'Seva')
                                        {{ $booking->seva->temple->name ?? 'N/A' }}
                                    @elseif($booking->type === 'Accommodation')
                                        {{ $booking->room->hotel->name ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $booking->status === 'Completed' || $booking->status === 'confirmed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ Str::ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>{{ $booking->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.bookings.show', ['type' => $booking->type, 'id' => $booking->id]) }}" class="btn btn-sm btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No bookings found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination links --}}
                <div class="mt-3 d-flex justify-content-center">
                    {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Inline CSS for pagination --}}
    <style>
        .pagination {
            font-size: 0.85rem;
        }

        .pagination .page-link {
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
        }

        .pagination .page-item.active .page-link {
            background-color: #4a148c;
            border-color: #4a148c;
            color: #fff;
        }

        .pagination .page-link:hover {
            background-color: #ede7f6;
        }
    </style>
@endsection
