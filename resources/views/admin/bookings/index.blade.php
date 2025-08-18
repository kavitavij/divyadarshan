@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>All Bookings</h1>
    <p>A combined list of all Darshan and Seva bookings across all temples, sorted by the most recent.</p>

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Type</th>
                        <th>Temple</th>
                        <th>User</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>
                            @if($booking->type === 'Darshan')
                                <span class="badge bg-info">Darshan</span>
                            @else
                                <span class="badge bg-success">Seva</span>
                            @endif
                        </td>
                        <td>
                            @if($booking->type === 'Darshan')
                                {{ $booking->temple->name ?? 'N/A' }}
                            @else
                                {{ $booking->seva->temple->name ?? 'N/A' }}
                            @endif
                        </td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>
                            @php
                                $statusClass = $booking->status === 'Confirmed' ? 'bg-success' : 'bg-warning';
                            @endphp
                            <span class="badge {{ $statusClass }}">{{ $booking->status }}</span>
                        </td>
                        <td>{{ $booking->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No bookings have been made yet on the website.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{-- Pagination links --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- Inline CSS for pagination --}}
<style>
    /* Make pagination smaller */
    .pagination {
        font-size: 0.85rem;
    }
    .pagination .page-link {
        padding: 0.25rem 0.6rem;
        border-radius: 4px;
    }
    .pagination .page-item.active .page-link {
        background-color: #4a148c; /* deep purple highlight */
        border-color: #4a148c;
        color: #fff;
    }
    .pagination .page-link:hover {
        background-color: #ede7f6;
    }
</style>
@endsection
