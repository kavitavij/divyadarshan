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

        /* Stat Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            color: #fff;
            padding: 20px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card .stat-icon {
            font-size: 3.5rem;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.2;
        }

        .stat-card-title {
            font-size: 2.5rem;
            font-weight: 700;
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

            {{-- Date Filter --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('hotel-manager.dashboard') }}" method="GET"
                        class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="flex-grow-1">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ $startDate instanceof \Carbon\Carbon ? $startDate->format('Y-m-d') : $startDate }}"
                                class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                value="{{ $endDate instanceof \Carbon\Carbon ? $endDate->format('Y-m-d') : $endDate }}"
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                        <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                    </form>
                </div>
            </div>

            {{-- Key Metrics --}}
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-success">
                        <div class="stat-icon">💰</div>
                        <h5 class="card-subtitle mb-2">Revenue (Period)</h5>
                        <h3 class="stat-card-title">₹{{ number_format($revenueForPeriod, 2) }}</h3>
                        <p class="card-text small">Completed bookings in period</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-info">
                        <div class="stat-icon">🧾</div>
                        <h5 class="card-subtitle mb-2">Bookings (Period)</h5>
                        <h3 class="stat-card-title">{{ $bookingsForPeriod }}</h3>
                        <p class="card-text small">New bookings in period</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-primary">
                        <div class="stat-icon">🚪</div>
                        <h5 class="card-subtitle mb-2">Total Rooms</h5>
                        <h3 class="stat-card-title">{{ $totalRooms }}</h3>
                        <p class="card-text small">In your hotel</p>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="row">
                <div class="col-lg-7 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Revenue Overview (Period)</h5>
                        </div>
                        <div class="card-body"><canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Bookings Overview (Period)</h5>
                        </div>
                        <div class="card-body"><canvas id="bookingsChart"></canvas></div>
                    </div>
                </div>
            </div>

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

@push('scripts')
    {{-- Chart.js for graphs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartData = JSON.parse('{!! $chartData !!}');

            // 1. Revenue Chart (Line)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Revenue',
                        data: chartData.revenue,
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₹' + value.toLocaleString();
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Revenue: ₹' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // 2. Bookings Chart (Bar)
            const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
            new Chart(bookingsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Bookings',
                        data: chartData.bookings,
                        backgroundColor: 'rgba(23, 162, 184, 0.7)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
