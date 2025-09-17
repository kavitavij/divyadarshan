@extends('layouts.hotel-manager')

@section('content')
    <style>
        .dashboard-container {
            background: #f9f9ff;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 20px;
        }

        /* Quick Actions using CSS Grid (always 2-3 per row) */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .quick-card {
            border-radius: 12px;
            border: none;
            background: #fff;
            transition: all 0.2s ease-in-out;
            padding: 20px;
            text-align: center;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }

        .quick-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.1);
        }

        .quick-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .quick-card .btn {
            border-radius: 6px;
            font-weight: 500;
            margin-top: 10px;
        }
    </style>

    <div class="dashboard-container container-fluid">

        <h1 class="dashboard-title mb-4">🏨 Hotel Manager Dashboard</h1>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        @if (isset($error))
            <div class="alert alert-danger shadow-sm">{{ $error }}</div>
        @else
            <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Manage your hotel with ease.</p>

            {{-- ✅ Quick Actions (2–3 per row, responsive) --}}
            <div class="quick-actions">
                <div class="quick-card">
                    <div class="quick-icon">✏️</div>
                    <h5 class="card-title">Edit Hotel</h5>
                    <p class="text-muted">Update hotel details and description.</p>
                    <a href="{{ route('hotel-manager.hotel.edit') }}" class="btn btn-primary">Manage Hotel</a>
                </div>

                <div class="quick-card">
                    <div class="quick-icon">🛏️</div>
                    <h5 class="card-title">Manage Rooms</h5>
                    <p class="text-muted">Add, edit, or remove hotel rooms.</p>
                    <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-info">Manage Rooms</a>
                </div>

                <div class="quick-card">
                    <div class="quick-icon">📖</div>
                    <h5 class="card-title">Bookings</h5>
                    <p class="text-muted">Track all guest bookings.</p>
                    <a href="{{ route('hotel-manager.guest-list.index') }}" class="btn btn-success">View Bookings</a>
                </div>

                <div class="quick-card">
                    <div class="quick-icon">➕</div>
                    <h5 class="card-title">Add Room</h5>
                    <p class="text-muted">Quickly add a new room.</p>
                    <a href="{{ route('hotel-manager.rooms.create') }}" class="btn btn-secondary">Add Room</a>
                </div>

                <div class="quick-card">
                    <div class="quick-icon">📊</div>
                    <h5 class="card-title">Reports</h5>
                    <p class="text-muted">View booking and revenue reports.</p>
                    <a href="{{ route('hotel-manager.revenue.index') }}" class="btn btn-dark">View Reports</a>
                </div>

                <div class="quick-card">
                    <div class="quick-icon">🛠️</div>
                    <h5 class="card-title">Refunds</h5>
                    <p class="text-muted">View Refund and Cancellation</p>
                    <a href="{{ route('hotel-manager.refund.index') }}" class="btn btn-warning">Refund</a>
                </div>
            </div>

            {{-- Recent Bookings --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Recent Bookings</h5>
                </div>
                <div class="card-body">
                    @if ($recentBookings->count())
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Guest Name</th>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentBookings as $booking)
                                        <tr>
                                            <td><span class="badge bg-success">#{{ $booking->id }}</span></td>
                                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->room->type ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No recent bookings found.</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
