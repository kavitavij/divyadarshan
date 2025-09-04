<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e293b;
            color: #fff;
            padding-top: 20px;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
    scrollbar-width: thin;
        }

        .sidebar h4 {
            padding-left: 20px;
            font-weight: bold;
            font-size: 18px;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #334155;
            color: #fff;
        }

        /* Sidebar Toggle */
        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        .collapsed+.main-content {
            margin-left: 70px;
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 12px 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar .profile-dropdown {
            position: relative;
        }

        .topbar .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .collapsed+.main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4 class="px-3">⚙️ Admin Panel</h4>
        <hr class="border-light">

        <ul class="nav flex-column">
            <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a>
            </li>
            <li><a class="nav-link {{ request()->routeIs('admin.temples.*') ? 'active' : '' }}"
                    href="{{ route('admin.temples.index') }}"><i class="fa-solid fa-church"></i>
                    <span>Temples</span></a>
            </li><li class="nav-item {{ request()->routeIs('admin.slots.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.slots.index') }}"><i class="fas fa-fw fa-clock"></i><span>Manage Slots</span></a>
                </li>
            <li><a class="nav-link {{ request()->routeIs('admin.ebooks.*') ? 'active' : '' }}"
                    href="{{ route('admin.ebooks.index') }}"><i class="fa-solid fa-book"></i> <span>Ebooks</span></a>
            </li>
            <li><a class="nav-link {{ request()->routeIs('admin.latest_updates.*') ? 'active' : '' }}"
                    href="{{ route('admin.latest_updates.index') }}"><i class="fa-solid fa-bullhorn"></i> <span>Latest
                        Updates</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}"
                    href="{{ route('admin.complaints.index') }}"><i class="fa-solid fa-exclamation-circle"></i>
                    <span>Complaints</span></a></li>
            <li> <a class="nav-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                    <i class="fa-solid fa-calendar-check"></i> <span>Darshan & Seva Bookings
                    </span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.bookings.accommodation') ? 'active' : '' }}" href="{{ route('admin.bookings.accommodation') }}">
                    <i class="fa-solid fa-bed"></i> <span>Accommodation Bookings</span>
                </a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.booking-cancel.*') ? 'active' : '' }}"
                    href="{{ route('admin.booking-cancel.index') }}"><i class="fa-solid fa-rotate-left"></i>
                    <span>Refund Return</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}"
                    href="{{ route('admin.donations.index') }}"><i class="fa-solid fa-hand-holding-heart"></i>
                    <span>Donations</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}"
                    href="{{ route('admin.hotels.index') }}"><i class="fa-solid fa-hotel"></i>
                    <span>Hotels</span></a></li>
            <li><a class="nav-link {{ request()->routeIs('admin.contact-submissions.*') ? 'active' : '' }}"
                    href="{{ route('admin.contact-submissions.index') }}"><i class="fa-solid fa-envelope"></i>
                    <span>Contact Forms</span></a></li>
            <li class="nav-item {{ request()->routeIs('admin.spiritual-help.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.spiritual-help.index') }}">
                    <i class="fas fa-fw fa-hands-helping"></i>
                    <span>Spiritual Help</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <main class="main-content" id="main">
        <!-- Topbar -->
        <div class="topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-bold">Welcome, {{ Auth::user()->name }} 🎉</h5>

            <div class="dropdown profile-dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="profileMenu" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fa-solid fa-user-circle"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="profileMenu">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Content -->
        <div class="mt-4">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');

        toggleBtn?.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>

</html>

