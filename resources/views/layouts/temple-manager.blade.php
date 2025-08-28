{{-- resources/views/temple_manager/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Temple Manager Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap for table & responsive utilities --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            height: 60px;
            background: #2c3e50;
            color: #fff;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background: #34495e;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #1abc9c;
            color: #fff;
        }

        /* Main content */
        .main {
            margin-left: 220px;
            padding: 20px;
        }

        /* Widgets */
        .widgets {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .widget {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .widget h3 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }

        .widget p {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Navbar --}}
    <div class="navbar">
        <div class="logo">Temple Manager</div>
        <div>
            <a href="{{ route('logout') }}" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="sidebar">
        <a href="{{ route('temple-manager.dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('temple-manager.bookings') }}">Bookings</a>
        <a href="{{ route('temple-manager.temples') }}">Manage Temple</a>
        <a href="#">Reports</a>
    </div>

    {{-- Main --}}
    <div class="main">
        <h1 class="mb-4">Welcome, Temple Manager</h1>

        {{-- Widgets --}}
        <div class="widgets">
            <div class="widget">
                <h3>150</h3>
                <p>Total Bookings</p>
            </div>
            <div class="widget">
                <h3>120</h3>
                <p>Confirmed</p>
            </div>
            <div class="widget">
                <h3>20</h3>
                <p>Pending</p>
            </div>
            <div class="widget">
                <h3>10</h3>
                <p>Cancelled</p>
            </div>
        </div>

        {{-- Table --}}
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

</body>

</html>
