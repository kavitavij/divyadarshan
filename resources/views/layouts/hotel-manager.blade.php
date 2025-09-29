<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hotel Manager | DivyaDarshan</title>
    <link rel="icon" type="image/png" href="/Divyadarshan/public/images/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>* {
            box-sizing: border-box;}
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            color: #2c3e50;
        }

        .navbar {
            background: #1e293b;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            color: #fff;
        }

        .navbar .brand {
            font-size: 22px;
            font-weight: bold;
            color: #fff;
        }

        .user-menu,
        .notification-bell {
            position: relative;
        }

        .user-btn {
            background: none;
            border: none;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-btn i {
            font-size: 14px;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 38px;
            background: #fff;
            color: #333;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            z-index: 1001;
        }

        .dropdown a,
        .dropdown button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 15px;
            border: none;
            background: none;
            color: #333;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .dropdown a:hover,
        .dropdown button:hover {
            background: #f1f1f1;
        }

        .user-menu.active .dropdown,
        .notification-bell.active .dropdown {
            display: block;
        }

        .sidebar {
            width: 240px;
            background: #1e293b;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 55px;
            left: 0;
            padding-top: 25px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            font-size: 15px;
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 16px;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #334155;
            color: #fff;
            border-left: 4px solid #facc15;
        }

        .content {
            margin-left: 240px;
            margin-top: 75px;
            padding: 25px;
            transition: all 0.3s;
        }

        .footer {
            text-align: center;
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            margin-left: 240px;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
            transition: all 0.3s;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .menu-toggle {
            display: none;
            font-size: 22px;
            background: none;
            border: none;
            cursor: pointer;
            color: #fff;
        }

        .notification-bell .badge {
            font-size: 10px;
        }

        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                left: -240px;
                top: 0;
                height: 100%;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0;
                margin-top: 80px;
            }

            .footer {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="navbar">
        <div class="brand">Hotel Manager Panel</div>
        <button class="menu-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>

        <div class="d-flex align-items-center ms-auto gap-3">
            
            <div class="notification-bell" id="notificationBell">
                <button onclick="toggleNotifications()" class="btn btn-link text-white p-0" style="font-size: 18px;">
                    <i class="fas fa-bell"></i>
                    <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 small rounded-circle" style="display: none;"></span>
                </button>
                <div class="dropdown" id="notificationDropdown" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                </div>
            </div>

            <div class="user-menu" id="userMenu">
                <button class="user-btn" onclick="toggleUserMenu()">
                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }} <i class="fas fa-caret-down"></i>
                </button>
                <div class="dropdown">
                    <a class="dropdown-item" href="{{ route('hotel-manager.profile.edit') }}"><i class="fas fa-user me-2"></i>Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button onclick="event.preventDefault(); this.closest('form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        {{-- Your sidebar links --}}
        <a href="{{ route('hotel-manager.dashboard') }}" class="{{ request()->routeIs('hotel-manager.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a>
        <a href="{{ route('hotel-manager.hotel.edit') }}" class="{{ request()->routeIs('hotel-manager.hotel.edit') ? 'active' : '' }}"><i class="fas fa-hotel"></i> My Hotel Details</a>
        <a href="{{ route('hotel-manager.rooms.index') }}" class="{{ request()->routeIs('hotel-manager.rooms.*') ? 'active' : '' }}"><i class="fas fa-bed"></i> Manage Rooms</a>
        <a href="{{ route('hotel-manager.guest-list.index') }}" class="{{ request()->routeIs('hotel-manager.guest-list.index') ? 'active' : '' }}"><i class="fas fa-book"></i> View Bookings</a>
        <a href="{{ route('hotel-manager.refund.index') }}" class="{{ request()->routeIs('hotel-manager.refund.index') ? 'active' : '' }}"><i class="fa-solid fa-money-check-dollar"></i> Refund</a>
        <a href="{{ route('hotel-manager.gallery.index') }}" class="{{ request()->routeIs('hotel-manager.gallery.index') ? 'active' : '' }}"><i class="fas fa-images"></i> Manage Gallery</a>
        <a href="{{ route('hotel-manager.revenue.index') }}" class="{{ request()->routeIs('hotel-manager.revenue.index') ? 'active' : '' }}"><i class="fas fa-dollar-sign"></i> Hotel Revenue</a>
        <a href="{{ route('hotel-manager.terms.edit') }}" class="{{ request()->routeIs('hotel-manager.terms.*') ? 'active' : '' }}"><i class="fas fa-file-contract"></i> Terms & Conditions</a>
    </div>
    <div class="content">@yield('content')</div>
    <div class="footer">&copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.</div>
    <script src="https://cdn.jsdelivrnet/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
    function toggleSidebar() { document.getElementById("sidebar").classList.toggle("active"); }
    function toggleUserMenu() { document.getElementById("userMenu").classList.toggle("active"); }
    function toggleNotifications() { document.getElementById("notificationBell").classList.toggle("active"); }

    // Handles closing dropdowns when clicking outside
    window.addEventListener('click', function(e) {
        const userMenu = document.getElementById("userMenu");
        const notifBell = document.getElementById("notificationBell");
        if (userMenu && !userMenu.contains(e.target)) { userMenu.classList.remove("active"); }
        if (notifBell && !notifBell.contains(e.target)) { notifBell.classList.remove("active"); }
    });
    const notificationCountEl = document.getElementById('notification-count');
    const notificationDropdownEl = document.getElementById('notificationDropdown');
    function formatTimeAgo(dateString) {
        const now = new Date();
        const notificationDate = new Date(dateString);
        const secondsAgo = Math.round((now - notificationDate) / 1000);

        if (secondsAgo < 60) { return "a few seconds ago"; }
        if (secondsAgo < 3600) {
            const minutes = Math.floor(secondsAgo / 60);
            return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
        }
        return notificationDate.toLocaleString('en-IN', { dateStyle: 'short', timeStyle: 'short' });
    }

    function fetchNotifications() {
        fetch('{{ route("hotel-manager.notifications.index") }}')
            .then(response => response.json())
            .then(notifications => {
                updateNotificationUI(notifications);
            });
    }

    function markAsRead(notificationId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch(`/hotel-manager/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        }).then(response => {
            if (response.ok) {
                fetchNotifications(); // Refresh the list
            }
        });
    }

    function updateNotificationUI(notifications) {
        if (notifications.length > 0) {
            notificationCountEl.innerText = notifications.length;
            notificationCountEl.style.display = 'block';
        } else {
            notificationCountEl.style.display = 'none';
        }

        let dropdownHtml = '<div class="p-2 fw-bold border-bottom">Notifications</div>';
        if (notifications.length === 0) {
            dropdownHtml += '<div class="p-3 text-center text-muted">No new notifications</div>';
        } else {
            notifications.forEach(notification => {
                // This now uses the correct function to format the date
                const date = formatTimeAgo(notification.created_at);
                dropdownHtml += `
                    <div class="p-2 border-bottom hover:bg-light">
                        <a href="${notification.data.url}" class="text-decoration-none text-dark d-block mb-1" style="font-size: 14px;">${notification.data.message}</a>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 12px;">${date}</span>
                            <button onclick="markAsRead('${notification.id}')" class="btn btn-sm btn-link text-primary p-0" style="font-size: 12px;">Mark as read</button>
                        </div>
                    </div>
                `;
            });
        }
        notificationDropdownEl.innerHTML = dropdownHtml;
    }

    // Initial fetch and polling
    document.addEventListener('DOMContentLoaded', function() {
        fetchNotifications();
        setInterval(fetchNotifications, 10000);
    });
</script>
    @stack('scripts')
</body>
</html>
