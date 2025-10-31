@extends('layouts.temple-manager')

@section('content')
    <style>
        .dashboard-title {
            font-size: 2rem;
            font-weight: 700;
        }

        .welcome-text {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 20px;
        }

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

        .temple-card {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        }
    </style>
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

            {{-- Date Filter --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('temple-manager.dashboard') }}" method="GET"
                        class="d-flex flex-wrap gap-2 align-items-end">
                        <div class="flex-grow-1">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" name="start_date"
                                value="{{ $startDate->format('Y-m-d') }}" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                                class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                        <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <p class="card-text small">From Darshan & Seva bookings</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-info">
                        <div class="stat-icon">🧾</div>
                        <h5 class="card-subtitle mb-2">Bookings (Period)</h5>
                        <h3 class="stat-card-title">{{ $bookingsForPeriod }}</h3>
                        <p class="card-text small">New bookings in selected period</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="stat-card bg-primary">
                        <div class="stat-icon">🕉️</div>
                        <h5 class="card-subtitle mb-2">All-Time Bookings</h5>
                        <h3 class="stat-card-title">{{ $allTimeBookingCount }}</h3>
                        <p class="card-text small">Total bookings for the temple</p>
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
                        <div class="card-body"><canvas id="revenueChart"></canvas></div>
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

            {{-- Recent Bookings Lists --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Darshan Bookings (in period)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse ($recentDarshanBookings as $booking)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                            ({{ $booking->number_of_people }} people)
                                            <small
                                                class="d-block text-muted">{{ $booking->created_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        <span class="badge bg-info rounded-pill">Order:
                                            {{ $booking->order->order_number ?? 'N/A' }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">No Darshan bookings found in this period.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Seva Bookings (in period)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @forelse ($recentSevaBookings as $booking)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $booking->user->name ?? 'N/A' }}</strong> -
                                            {{ $booking->seva->name ?? 'N/A' }}
                                            <small
                                                class="d-block text-muted">{{ $booking->created_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        <span class="badge bg-warning rounded-pill">Order:
                                            {{ $booking->order->order_number ?? 'N/A' }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center">No Seva bookings found in this period.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Card --}}
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h5 class="mb-0">⚡ Quick Actions for {{ $temple->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="btn-group gap-2 flex-wrap">
                        <a href="{{ route('temple-manager.temple.edit') }}" class="btn btn-outline-primary">✏️ Edit
                            Temple</a>
                        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                            data-bs-target="#termsModal">📄 Manage T&C</button>
                        <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-outline-secondary">🗓️ Manage
                            Slots</a>
                        <a href="{{ route('temple-manager.sevas.index') }}" class="btn btn-outline-success">🙏 Manage
                            Sevas</a>
                        <a href="{{ route('temple-manager.bookings.index') }}" class="btn btn-outline-dark">📖 View
                            Bookings</a>
                        <a href="{{ route('temple-manager.gallery.index') }}" class="btn btn-outline-warning">🖼️
                            Gallery</a>
                    </div>
                </div>
            </div>
    </div>
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">Add, edit, or remove terms. Each line will appear as a numbered list item
                                to the user.</p>
                            <div id="terms-container">
                                @if ($temple->terms_and_conditions)
                                    @foreach ($temple->terms_and_conditions as $term)
                                        <div class="input-group mb-2">
                                            <input type="text" name="terms_and_conditions[]" class="form-control"
                                                value="{{ $term }}">
                                            <button class="btn btn-outline-danger remove-term-btn"
                                                type="button">Remove</button>
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
    {{-- Chart.js for graphs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($temple)
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
            @endif
        });
    </script>
    <script>
        document.addEventListener('click', function(e) {
            const addBtn = e.target.closest('.add-term-btn');
            const removeBtn = e.target.closest('.remove-term-btn');

            if (addBtn) {
                const container = document.getElementById('terms-container');
                if (container) {
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
