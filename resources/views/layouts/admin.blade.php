<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-width-collapsed: 70px;
        }

        body {
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #1e293b;
            color: #fff;
            padding-top: 20px;
            overflow-y: auto;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
            scrollbar-width: thin;
        }

        .sidebar h4 {
            padding-left: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar-heading {
            padding: 10px 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
            white-space: nowrap;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 12px 20px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            white-space: nowrap;
            transition: all 0.2s ease-in-out;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #334155;
            color: #fff;
        }

        .sidebar .nav-link span {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        /* Collapsed state for desktop */
        .sidebar.collapsed {
            width: var(--sidebar-width-collapsed);
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed h4,
        .sidebar.collapsed .sidebar-heading,
        .sidebar.collapsed hr {
            opacity: 0;
            display: none;
        }

        /* Main content area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            width: calc(100% - var(--sidebar-width));
            transition: margin-left 0.3s ease-in-out;
            position: relative;
        }

        .sidebar.collapsed+.main-content {
            margin-left: var(--sidebar-width-collapsed);
            width: calc(100% - var(--sidebar-width-collapsed));
        }

        /* Top bar */
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

        .topbar .dropdown-menu {
            right: 0;
            left: auto;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            transition: opacity 0.3s ease-in-out;
        }

        /* Mobile specific styles (below 992px) */
        @media (max-width: 991.98px) {
            .sidebar {
                left: 0;
            }

            .sidebar.collapsed {
                left: calc(-1 * var(--sidebar-width));
                width: var(--sidebar-width);
            }

            .sidebar.collapsed .nav-link span,
            .sidebar.collapsed h4,
            .sidebar.collapsed .sidebar-heading,
            .sidebar.collapsed hr {
                opacity: 1;
                display: block;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar.collapsed+.main-content {
                margin-left: 0;
                width: 100%;
            }

            .desktop-toggle {
                display: none;
            }
        }

        /* Desktop specific styles (992px and up) */
        @media (min-width: 992px) {
            .mobile-toggle {
                display: none;
            }

            .sidebar-overlay {
                display: none;
            }
        }
    </style>
</head>

<body x-data="sidebar()" @resize.window="onResize()">

    {{-- Sidebar --}}
    <nav class="sidebar" :class="{ 'collapsed': !isOpen }">
        <h4 class="px-3">⚙️ Admin Panel</h4>
        <hr class="border-light">
        <ul class="nav flex-column">
            {{-- Links --}}
            <li><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.managers.*') ? 'active' : '' }}"
                    href="{{ route('admin.managers.index') }}">
                    <i class="fa-solid fa-user"></i> <span>View Managers</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}"
                    href="{{ route('admin.announcements.create') }}">
                    <i class="fa-solid fa-bullhorn"></i> <span>Announcements</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}"
                    href="{{ route('admin.revenue.index') }}">
                    <i class="fas fa-dollar-sign"></i> <span>Revenue</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                    href="{{ route('admin.settings.edit') }}">
                    <i class="fa-solid fa-file-alt"></i> <span>Page Content</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.contact-submissions.*') ? 'active' : '' }}"
                    href="{{ route('admin.contact-submissions.index') }}">
                    <i class="fa-solid fa-envelope"></i> <span>Contact Forms</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.latest_updates.*') ? 'active' : '' }}"
                    href="{{ route('admin.latest_updates.index') }}">
                    <i class="fa-solid fa-bullhorn"></i> <span>Latest Updates</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.complaints.*') ? 'active' : '' }}"
                    href="{{ route('admin.complaints.index') }}">
                    <i class="fa-solid fa-exclamation-circle"></i> <span>Complaints</span></a></li>

            {{-- Temple Management Heading --}}
            <li class="sidebar-heading">Temple Management</li>
            <li><a class="nav-link {{ request()->routeIs('admin.temples.*') ? 'active' : '' }}"
                    href="{{ route('admin.temples.index') }}">
                    <i class="fa-solid fa-church"></i> <span>Temples</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.ebooks.*') ? 'active' : '' }}"
                    href="{{ route('admin.ebooks.index') }}">
                    <i class="fa-solid fa-book"></i> <span>Ebooks</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.spiritual-help.index') ? 'active' : '' }}"
                    href="{{ route('admin.spiritual-help.index') }}">
                    <i class="fas fa-hands-helping"></i> <span>Spiritual Help</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.bookings.index') ? 'active' : '' }}"
                    href="{{ route('admin.bookings.index') }}">
                    <i class="fa-solid fa-calendar-check"></i> <span>Darshan & Seva Bookings</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}"
                    href="{{ route('admin.donations.index') }}">
                    <i class="fa-solid fa-hand-holding-heart"></i> <span>Donations</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.slots.*') ? 'active' : '' }}"
                    href="{{ route('admin.slots.index') }}">
                    <i class="fas fa-clock"></i> <span>Manage Slots</span></a></li>

            {{-- Hotel Management Heading --}}
            <li class="sidebar-heading">Hotel Management</li>
            <li><a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}"
                    href="{{ route('admin.hotels.index') }}">
                    <i class="fa-solid fa-hotel"></i> <span>Hotels</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.amenities.index') ? 'active' : '' }}"
                    href="{{ route('admin.amenities.index') }}">
                    <i class="fas fa-concierge-bell"></i> <span>Manage Amenities</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.bookings.accommodation') ? 'active' : '' }}"
                    href="{{ route('admin.bookings.accommodation') }}">
                    <i class="fa-solid fa-bed"></i> <span>Accommodation Bookings</span></a></li>

            <li><a class="nav-link {{ request()->routeIs('admin.booking-cancel.*') ? 'active' : '' }}"
                    href="{{ route('admin.booking-cancel.index') }}">
                    <i class="fa-solid fa-rotate-left"></i> <span>Refund Return</span></a></li>
        </ul>
    </nav>

    <!-- Main Content Wrapper -->
    <main class="main-content" id="main">
        <div class="topbar">
            <div class="d-flex align-items-center">
                {{-- Single unified toggle button --}}
                <button class="btn btn-sm btn-outline-secondary me-2" @click="toggle()">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- UPDATED: Welcome message for desktop -->
                <h5 class="mb-0 fw-bold ms-2 d-none d-sm-block">Welcome, {{ Auth::user()->name }} 🎉</h5>

                <!-- NEW: Title for mobile -->
                <h5 class="mb-0 fw-bold ms-2 d-sm-none" style="color: #1e293b;">Admin Panel</h5>
            </div>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="profileMenu" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fa-solid fa-user-circle"></i>
                    <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenu">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Mobile overlay, shown when sidebar is open on mobile -->
        <div class="sidebar-overlay lg:hidden" x-show="isOpen" @click="isOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>

        <!-- Content from child pages will go here -->
        <div class="p-4">
            @yield('content')
        </div>
    </main>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Unified Alpine Sidebar Logic -->
    <script>
        function sidebar() {
            return {
                // Set initial state based on screen size
                isOpen: window.innerWidth >= 992,

                // Toggle the sidebar
                toggle() {
                    this.isOpen = !this.isOpen;
                },

                // Close sidebar if window is resized to mobile
                onResize() {
                    if (window.innerWidth < 992) {
                        this.isOpen = false;
                    }
                }
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
