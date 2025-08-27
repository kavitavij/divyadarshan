<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: row;
        }

        /* Desktop Sidebar */
        .sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .main-content {
            margin-left: 280px;
            padding: 20px;
            width: 100%;
        }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            /* Show Toggle Button */
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                background: #f8f9fa;
                border-bottom: 1px solid #ddd;
                position: sticky;
                top: 0;
                z-index: 1050;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <h5 class="mb-0">Admin Panel</h5>
        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            ☰ Menu
        </button>
    </div>

    <!-- Sidebar (Desktop) -->
    <div class="sidebar border-end d-none d-lg-block">
        <h4 class="px-3">Admin Panel</h4>
        <hr>
        <ul class="nav flex-column">
            <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a class="nav-link" href="{{ route('admin.temples.index') }}">Manage Temples</a></li>
            <li><a class="nav-link" href="{{ route('admin.ebooks.index') }}">Manage Ebooks</a></li>
            <li><a class="nav-link" href="{{ route('admin.latest_updates.index') }}">Latest Updates</a></li>
            <li><a class="nav-link" href="{{ route('admin.complaints.index') }}">Manage Complaints</a></li>
            <li><a class="nav-link" href="{{ route('admin.bookings.index') }}">Bookings</a></li>
            <li><a class="nav-link" href="{{ route('admin.hotels.index') }}">Hotels</a></li>
            <li><a class="nav-link" href="{{ route('admin.contact-submissions.index') }}">Contact Forms</a></li>
        </ul>
        <hr>
        <div class="px-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Offcanvas Sidebar (Mobile) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Admin Panel</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="nav flex-column">
                <li><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a class="nav-link" href="{{ route('admin.temples.index') }}">Manage Temples</a></li>
                <li><a class="nav-link" href="{{ route('admin.ebooks.index') }}">Manage Ebooks</a></li>
                <li><a class="nav-link" href="{{ route('admin.latest_updates.index') }}">Latest Updates</a></li>
                <li><a class="nav-link" href="{{ route('admin.complaints.index') }}">Manage Complaints</a></li>
                <li><a class="nav-link" href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                <li><a class="nav-link" href="{{ route('admin.hotels.index') }}">Hotels</a></li>
                <li><a class="nav-link" href="{{ route('admin.contact-submissions.index') }}">Contact Forms</a></li>
            </ul>
            <hr>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger w-100">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
