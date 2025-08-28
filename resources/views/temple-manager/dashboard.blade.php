@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-title mb-4">Temple Manager Dashboard</h1>

        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        @if (isset($error))
            <div class="alert alert-danger shadow-sm">{{ $error }}</div>
        @else
            <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Manage your temple with ease.</p>

            <div class="card shadow-lg temple-card mt-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        Your Temple: <strong>{{ $temple->name }}</strong>
                    </h3>
                </div>
                <div class="card-body">
                    <p class="temple-description">{{ $temple->description }}</p>
                    <hr>
                    <h5 class="mb-3">⚡ Quick Actions</h5>
                    <div class="btn-group gap-2 flex-wrap">
                        <a href="{{ route('temple-manager.temple.edit') }}" class="btn btn-outline-primary">
                            ✏️ Edit Temple Details
                        </a>
                        <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-outline-info">
                            🗓️ Manage Darshan Slots
                        </a>
                        <a href="{{ route('temple-manager.sevas.index') }}" class="btn btn-outline-secondary">
                            🙏 Manage Sevas
                        </a>
                        <a href="{{ route('temple-manager.darshan-bookings.index') }}" class="btn btn-outline-success">
                            📖 View Darshan Bookings
                        </a>
                    </div>
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
                                            <small
                                                class="d-block text-muted">{{ $booking->created_at->format('d M Y, h:i A') }}</small>
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
                                            <small
                                                class="d-block text-muted">{{ $booking->created_at->format('d M Y, h:i A') }}</small>
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
        @endif
    </div>
    <h2>Recent Bookings</h2>
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
                @foreach ($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->date }}</td>
                        <td>{{ $booking->slot?->time ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                @if ($bookings->isEmpty())
                    <tr>
                        <td colspan="5">No recent bookings found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    </div>
@endsection
