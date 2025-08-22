<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Temple Manager | DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Manual CSS Styling -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
        }

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

        .navbar .search input {
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .navbar .logout button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .sidebar {
            width: 220px;
            background-color: #2c3e50;
            color: #ecf0f1;
            height: 100vh;
            position: fixed;
            top: 50px;
            left: 0;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #34495e;
        }

        .content {
            margin-left: 220px;
            margin-top: 70px;
            padding: 20px;
        }

        .dashboard-widgets {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .widget {
            background-color: #3498db;
            color: white;
            padding: 20px;
            border-radius: 8px;
            flex: 1;
            min-width: 200px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .widget h3 {
            margin: 0;
            font-size: 28px;
        }

        .widget p {
            margin: 5px 0 0;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            margin-left: 220px;
            margin-top: 40px;
        }

        a.brand-link {
            color: #ecf0f1;
            font-weight: bold;
            text-align: center;
            display: block;
            padding: 10px 0;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="navbar">
        <div class="brand">Temple Manager Panel</div>

        <div class="logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button onclick="event.preventDefault(); this.closest('form').submit();">Log Out</button>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="{{ route('temple-manager.dashboard') }}"
            class="{{ request()->routeIs('temple-manager.dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('temple-manager.temple.edit') }}"
            class="{{ request()->routeIs('temple-manager.temple.edit') ? 'active' : '' }}">My temple Details</a>
        <a href="{{ route('temple-manager.slots.index') }}"
            class="{{ request()->routeIs('temple-manager.slots.index*') ? 'active' : '' }}">Manage Darshan Slots</a>
        <a href="{{ route('temple-manager.sevas.index') }}"
            class="{{ request()->routeIs('temple-manager.sevas.index') ? 'active' : '' }}">Manage Sevas</a>

    </div>

    <!-- Content -->
    <div class="content">
        <h1>Dashboard</h1>

        <div class="dashboard-widgets">
            <div class="widget" style="background-color: #1abc9c;">
                <h3>120</h3>
                <p>Active Bookings</p>
            </div>
            <div class="widget" style="background-color: #e67e22;">
                <h3>45</h3>
                <p>Available Rooms</p>
            </div>
            <div class="widget" style="background-color: #9b59b6;">
                <h3>₹1,20,000</h3>
                <p>Monthly Revenue</p>
            </div>
        </div>

        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.
    </div>

</body>

</html>
