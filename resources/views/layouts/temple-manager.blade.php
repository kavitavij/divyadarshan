<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Temple Manager Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"W/>
    <link rel="icon" href="{{ asset('favicon.ico') }}"/>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" crossorigin="anonymous"/>
    @stack('styles')

    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            color: #333;
        }
        .footer {
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            margin-left: 220px;
            margin-top: 40px;
            transition: all 0.3s;
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
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        .navbar .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
            color: #fff;
        }
        .navbar .menu-toggle:focus {
            outline: 2px solid #1abc9c;
            outline-offset: 2px;
        }

        .logo {
            font-weight: 700;
            font-size: 1.3rem;
            user-select: none;
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
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ecf0f1;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.25s ease, color 0.25s ease;
            border-left: 4px solid transparent;
        }
        .sidebar a:hover,
        .sidebar a:focus,
        .sidebar a.active {
            background: #1abc9c;
            color: #fff;
            outline: none;
            border-left-color: #16a085;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .main {
            margin-left: 220px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            min-height: calc(100vh - 60px);
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
        }
        .widget:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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
            transition: opacity 0.3s ease;
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
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar" role="navigation" aria-label="Main Navigation">
        <button
          class="menu-toggle"
          aria-label="Toggle sidebar menu"
          aria-expanded="false"
          aria-controls="sidebar"
          onclick="toggleSidebar(event)"
          onkeydown="if(event.key==='Enter') toggleSidebar(event)">☰</button>

        <div class="logo" aria-label="Temple Manager Dashboard">Temple Manager</div>

        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button
                    class="btn btn-sm btn-outline-light dropdown-toggle"
                    type="button"
                    id="userMenuButton"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                >
                    <i class="fas fa-user-circle"></i> Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuButton">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar" aria-label="Sidebar menu">
        <a href="{{ route('temple-manager.dashboard') }}" 
        class="{{ request()->routeIs('temple-manager.dashboard') ? 'active' : '' }}"> Dashboard</a>
        <a href="{{ route('temple-manager.slots.index') }}" 
        class="{{ request()->routeIs('temple-manager.slots*') ? 'active' : '' }}">
        <i class="fas fa-calendar-alt"></i> Manage Slots</a>
        <a href="{{ route('temple-manager.sevas.index') }}" 
        class="{{ request()->routeIs('temple-manager.sevas*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-heart"></i> Manage Sevas</a>
        <a href="{{ route('temple-manager.bookings.index') }}" 
        class="{{ request()->routeIs('temple-manager.bookings*') ? 'active' : '' }}">
        <i class="fas fa-calendar-check"></i> Bookings</a>
        <a href="{{ route('temple-manager.temple.edit') }}" 
        class="{{ request()->routeIs('temple-manager.temples*') ? 'active' : '' }}">
        <i class="fas fa-building"></i> Manage Temple</a>
        <a href="{{ route('temple-manager.gallery.index') }}" 
        class="{{ request()->routeIs('temple-manager.gallery*') ? 'active' : '' }}">
        <i class="fas fa-images"></i> Manage Gallery</a>
        <a href="{{ route('temple-manager.revenue.index') }}" 
        class="{{ request()->routeIs('temple-manager.revenue*') ? 'active' : '' }}">
        <i class="fas fa-dollar-sign"></i> Temple Revenue</a>
    </nav>

    {{-- Overlay for mobile --}}
    <div class="overlay" id="overlay" onclick="closeSidebar()" tabindex="-1" aria-hidden="true"></div>

    {{-- Main Content --}}
    <main class="main" role="main">
        @yield('content')
    </main>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous" defer></script>

    <script defer>
        function toggleSidebar(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const btn = event.currentTarget;

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            // Update aria-expanded on button for accessibility
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', !expanded);
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('overlay').classList.remove('active');

            // Reset aria-expanded on toggle button
            const toggleBtn = document.querySelector('.menu-toggle');
            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', false);
        }
    </script>

    @stack('scripts')
</body>
<div class="footer">
        &copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.
    </div>
</html>
