@extends('layouts.temple-manager')

@section('content')
<div class="container-fluid">
    <h1 class="dashboard-title mb-4">Temple Manager Dashboard</h1>

    {{-- Success & Error Alerts --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    @if ($temple)
        {{-- Welcome --}}
        <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Manage your temple with ease.</p>

        {{-- Temple Details Card --}}
        <div class="card shadow-lg temple-card mt-4">
            <div class="card-header">
                <h3 class="card-title mb-0">Your Temple: <strong>{{ $temple->name }}</strong></h3>
            </div>
            <div class="card-body">
                <p class="temple-description">{{ $temple->description }}</p>
                <hr>
                <h5 class="mb-3">⚡ Quick Actions</h5>
                <div class="btn-group gap-2 flex-wrap">
                    <a href="{{ route('temple-manager.temple.edit') }}" class="btn btn-outline-primary">✏️ Edit Temple</a>
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#termsModal">
                        📄 Manage T&C
                    </button>
                    <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-outline-primary">🗓️ Manage Slots</a>
                    <a href="{{ route('temple-manager.sevas.index') }}" class="btn btn-outline-primary">🙏 Manage Sevas</a>
                    <a href="{{ route('temple-manager.bookings.index') }}" class="btn btn-outline-primary">📖 View Bookings</a>
                    <a href="{{ route('temple-manager.gallery.index') }}" class="btn btn-outline-primary">Gallery</a>
                </div>
            </div>
        </div>

        {{-- Widgets --}}
        <div class="widgets mt-4">
            <div class="widget">
                <h3>{{ $darshanBookings->count() + $sevaBookings->count() }}</h3>
                <p>Total Recent Bookings</p>
            </div>
            <div class="widget">
                <h3>{{ $darshanBookings->count() }}</h3>
                <p>Darshan Bookings</p>
            </div>
            <div class="widget">
                <h3>{{ $sevaBookings->count() }}</h3>
                <p>Seva Bookings</p>
            </div>
            <div class="widget">
                <h3>{{ $allTimeBookingCount }}</h3>
                <p>All-Time Bookings</p>
            </div>
        </div>

        {{-- Recent Bookings Section --}}
        <div class="row mt-4">
            {{-- Recent Darshan Bookings --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Darshan Bookings</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse ($darshanBookings as $booking)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                        ({{ $booking->number_of_people }} people)
                                        <small class="d-block text-muted">
                                            {{ $booking->created_at->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-info rounded-pill">ID: {{ $booking->id }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No recent Darshan bookings found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Recent Seva Bookings --}}
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Recent Seva Bookings</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse ($sevaBookings as $booking)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $booking->user->name ?? 'N/A' }}</strong> -
                                        {{ $booking->seva->name ?? 'N/A' }}
                                        <small class="d-block text-muted">
                                            {{ $booking->created_at->format('d M Y, h:i A') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-warning rounded-pill">ID: {{ $booking->id }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center">No recent Seva bookings found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Bookings Table --}}
        <h2 class="mt-5">All Recent Bookings</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Devotee</th>
                        <th>Darshan Date</th>
                        <th>Slot</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $index => $booking)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $booking->user->name }}</td>
                            {{-- FIX 1: Use the correct column 'booking_date' --}}
                            <td>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d M, Y') : 'N/A' }}</td>

                            {{-- FIX 2: Use the new smart 'slot_time' attribute from the model --}}
                            <td>{{ $booking->slot_time }}</td>

                            <td>
                                <span class="badge bg-{{ $booking->status == 'Confirmed' ? 'success' : 'warning' }} text-capitalize">
                                    {{ $booking->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No recent bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-warning">You are not assigned a temple. Please contact an administrator.</div>
    @endif
</div>

{{-- T&C Modal --}}
@if ($temple)
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('temple-manager.temple.update') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_source" value="terms_modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Manage T&C for {{ $temple->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Add, edit, or remove terms. Each line will appear as a numbered list item to the user.</p>
                    <div id="terms-container">
                        @if($temple->terms_and_conditions)
                            @foreach($temple->terms_and_conditions as $term)
                                <div class="input-group mb-2">
                                    <input type="text" name="terms_and_conditions[]" class="form-control" value="{{ $term }}">
                                    <button class="btn btn-outline-danger remove-term-btn" type="button">Remove</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-outline-success btn-sm mt-2 add-term-btn">
                        <i class="fas fa-plus"></i> Add Term
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const addBtn = e.target.closest('.add-term-btn');
    const removeBtn = e.target.closest('.remove-term-btn');

    if (addBtn) {
        const container = document.getElementById('terms-container');
        if(container) {
            const newTermDiv = document.createElement('div');
            newTermDiv.className = 'input-group mb-2';
            newTermDiv.innerHTML = `
                <input type="text" name="terms_and_conditions[]" class="form-control" placeholder="Enter a new term">
                <button class="btn btn-outline-danger remove-term-btn" type="button">Remove</button>
            `;
            container.appendChild(newTermDiv);
        }
    }

    if (removeBtn) {
        removeBtn.closest('.input-group').remove();
    }
});
</script>
@endpush
