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
        }

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
    </style>
</head>

<body>
    <div class="sidebar border-end">
        <h4 class="px-3">Admin Panel</h4>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
            </li>
            <a class="nav-link" href="{{ route('admin.temples.index') }}">Manage Temples</a>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.ebooks.index') }}">Manage Ebooks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.latest_updates.index') }}">Latest Updates</a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('admin.complaints.index') }}">Manage Complaints</a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('admin.bookings.index') }}">Bookings </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('admin.hotels.index') }}">Hotels </a>
            </li>
            <li>
                <a class="nav-link" href="{{ route('admin.contact-submissions.index') }}">Contact Forms</a>
            </li>
        </ul>
        <hr>

        <div class="px-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">Log Out</button>
            </form>
        </div>
    </div>

    <main class="main-content">
        @yield('content')
    </main>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
</body>

</html>
