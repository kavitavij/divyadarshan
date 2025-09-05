<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hotel Manager | DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        .navbar .brand {
            font-size: 20px;
            font-weight: bold;
        }

        .navbar .logout button {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            transition: 0.3s;
        }

        .navbar .logout button:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: scale(1.05);
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            position: fixed;
            top: 50px;
            left: 0;
            padding-top: 20px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #34495e;
        }

        /* Content */
        .content {
            margin-left: 220px;
            margin-top: 70px;
            padding: 20px;
            transition: all 0.3s;
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            margin-left: 220px;
            margin-top: 40px;
            transition: all 0.3s;
        }

        /* Hamburger menu for mobile */
        .menu-toggle {
            display: none;
            font-size: 22px;
            background: none;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                left: -220px;
                top: 0;
                height: 100%;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                margin-top: 70px;
            }

            .footer {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="brand">Hotel Manager Panel</div>
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button onclick="event.preventDefault(); this.closest('form').submit();">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{ route('hotel-manager.dashboard') }}"
            class="{{ request()->routeIs('hotel-manager.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('hotel-manager.hotel.edit') }}"
            class="{{ request()->routeIs('hotel-manager.hotel.edit') ? 'active' : '' }}">My Hotel Details</a>
        <a href="{{ route('hotel-manager.rooms.index') }}"
            class="{{ request()->routeIs('hotel-manager.rooms.*') ? 'active' : '' }}">Manage Rooms</a>
        <a href="{{ route('hotel-manager.guest-list.index') }}"
            class="{{ request()->routeIs('hotel-manager.guest-list.index') ? 'active' : '' }}">View Bookings</a>
    <li class="nav-item">
    <a class="nav-link" href="{{ route('hotel-manager.gallery.index') }}">
        <i class="fas fa-fw fa-images"></i>
        <span>Manage Gallery</span>
    </a>
</li>
        </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }
    </script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
 <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdR-7EGvRdTcL0NSvxG1pKan2bQu3nXuo&libraries=places&callback=initMap" async defer></script>@stack('scripts')

</body>

</html>
