<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Temple Manager Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }
        .navbar {
            height: 60px;
            background: #2c3e50;
            color: #fff;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1100;
        }
        .navbar .menu-toggle {
            display: none;
            font-size: 22px;
            cursor: pointer;
        }
        .sidebar {
            width: 220px;
            background: #34495e;
            position: fixed;
            top: 60px;
            left: 0;
            bottom: 0;
            padding-top: 20px;
            transition: transform 0.3s ease;
            z-index: 1200;
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
        .main {
            margin-left: 220px;
            padding: 20px;
        }
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
        .overlay {
            display: none;
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1100;
        }
        .overlay.active {
            display: block;
        }
        @media (max-width: 768px) {
            .navbar .menu-toggle {
                display: block;
            }
            .sidebar {
                width: 200px;
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
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
        <div class="menu-toggle" onclick="toggleSidebar()">☰</div>
        <div class="logo">Temple Manager</div>
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger">Logout</button>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="sidebar" id="sidebar">
        <a href="{{ route('temple-manager.dashboard') }}"
            class="{{ request()->routeIs('temple-manager.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('temple-manager.bookings.index') }}"
            class="{{ request()->routeIs('temple-manager.bookings*') ? 'active' : '' }}">Bookings</a>
        <a href="{{ route('temple-manager.temple.edit') }}"
            class="{{ request()->routeIs('temple-manager.temples*') ? 'active' : '' }}">Manage Temple</a>
        <a href="#">Reports</a>
    </div>

    {{-- Overlay --}}
    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    {{-- Main Content --}}
    <div class="main">
        @yield('content')
    </div>

    {{-- Main Sidebar Toggle Script --}}
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');
        }
    </script>

    {{-- Bootstrap JS for Modals and other components --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- THE FIX: This line will render any scripts pushed from child views --}}
    @stack('scripts')

</body>
</html>
