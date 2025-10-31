@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">Dashboard Overview</h4>
            <span class="text-muted">{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
        </div>

        {{-- Date Filter --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-end">
                    <div class="flex-grow-1">
                        <label for="start_date" class="form-label"></label>Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                            class="form-control">
                    </div>
                    <div class="flex-grow-1">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                            class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Reset</a>
                </form>
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Revenue (Period)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    ₹{{ number_format($revenueForPeriod, 2) }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-rupee-sign fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Bookings (Period)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($bookingsForPeriod) }}
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-calendar-check fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">New Users (Period)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($newUsersForPeriod) }}
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Complaints
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pendingComplaints) }}
                                </div>
                            </div>
                            <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Bookings Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="bookingsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <h5 class="mt-4 mb-3 text-secondary">Quick Actions</h5>
        {{-- General Website Section --}}
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Managers</h5>
                        <p class="text-muted">Add and view all managers.</p>
                        <a href="{{ route('admin.managers.index') }}" class="btn btn-primary">Manage Managers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Announcement</h5>
                        <p class="text-muted">Make Announcement for users</p>
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">Website
                            announcements</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Revenue</h5>
                        <p class="text-muted">View Revenue of website</p>
                        <a href="{{ route('admin.revenue.index') }}" class="btn btn-primary">Website Revenue</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Page Content</h5>
                        <p class="text-muted">View and add page content.</p>
                        <a href="{{ route('admin.settings.edit') }}" class="btn btn-primary">Manage Page Contents</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Contact Messages</h5>
                        <p class="text-muted">Read and respond to user messages.</p>
                        <a href="{{ route('admin.contact-submissions.index') }}" class="btn btn-success">View
                            Messages</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Latest Updates</h5>
                        <p class="text-muted">Share announcements and news.</p>
                        <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-warning text-white">Latest
                            Updates</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Complaints</h5>
                        <p class="text-muted">View and resolve user complaints.</p>
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-danger">Manage Complaints</a>
                    </div>
                </div>
            </div>
        </div>
        {{-- Temple Management Section --}}
        <h5 class="mt-4 mb-3 text-success">Temple Management</h5>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Temples</h5>
                        <p class="text-muted">Add, edit, or remove temple details.</p>
                        <a href="{{ route('admin.temples.index') }}" class="btn btn-primary">Manage Temples</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">E-Books</h5>
                        <p class="text-muted">Upload and manage e-book resources.</p>
                        <a href="{{ route('admin.ebooks.index') }}" class="btn btn-success">Manage E-Books</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Spiritual Help</h5>
                        <p class="text-muted">Review and manage spiritual help forms.</p>
                        <a href="{{ route('admin.spiritual-help.index') }}" class="btn btn-primary">Spiritual Help</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Darshan & Sevas Booking</h5>
                        <p class="text-muted">Manage temple darshan and seva bookings.</p>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Manage Bookings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Temple Donations</h5>
                        <p class="text-muted">View and manage temple donations.</p>
                        <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">Donations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Slots</h5>
                        <p class="text-muted">Add or edit temple darshan & seva time slots.</p>
                        <a href="{{ route('admin.slots.index') }}" class="btn btn-primary">Manage Slots</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Accommodation Section --}}
        <h5 class="mt-4 mb-3 text-info">Hotel Management</h5>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Hotels</h5>
                        <p class="text-muted">Add and manage hotels/accommodations.</p>
                        <a href="{{ route('admin.hotels.index') }}" class="btn btn-secondary">Manage Hotels</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Manage Amenities</h5>
                        <p class="text-muted">Add or edit hotel amenities.</p>
                        <a href="{{ route('admin.amenities.index') }}" class="btn btn-primary">Manage Amenities</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Accommodation Bookings</h5>
                        <p class="text-muted">Manage hotel room/accommodation bookings.</p>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Manage Bookings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cancel Requests</h5>
                        <p class="text-muted">Process booking cancellations and refunds.</p>
                        <a href="{{ route('admin.booking-cancel.index') }}" class="btn btn-info">Refund & Return</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Chart.js for beautiful charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Common Chart Options
            const commonOptions = {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000) {
                                    return '₹' + (value / 1000) + 'k';
                                }
                                return '₹' + value;
                            }
                        }
                    }
                }
            };

            // 1. Revenue Chart
            var ctxRevenue = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctxRevenue, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: "Revenue",
                        data: @json($revenueChartData),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: commonOptions
            });

            // 2. Bookings Chart
            var ctxBookings = document.getElementById('bookingsChart').getContext('2d');
            new Chart(ctxBookings, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: "Bookings",
                        data: @json($bookingChartData),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
