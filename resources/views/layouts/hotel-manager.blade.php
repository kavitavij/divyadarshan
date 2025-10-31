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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Base Styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f9;
            color: #2c3e50;
        }

        /* Navbar */
        .navbar {
            background: #1e293b;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            /* CHANGED: sticky is often better than fixed */
            top: 0;
            z-index: 1030;
            /* Bootstrap's standard z-index for navbars */
            color: #fff;
        }

        .navbar .brand {
            font-size: 22px;
            font-weight: bold;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: #1e293b;
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 61px;
            /* Match navbar height */
            left: 0;
            padding-top: 25px;
            transition: transform 0.3s ease-in-out;
            z-index: 1020;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
            font-size: 15px;
            border-left: 4px solid transparent;
            /* Prepare for active state */
        }

        .sidebar a i {
            margin-right: 12px;
            font-size: 16px;
            width: 20px;
            /* Align icons nicely */
            text-align: center;
        }

        .sidebar a:hover,
        .sidebar .active {
            background-color: #334155;
            border-left-color: #facc15;
        }

        /* Main Content & Footer */
        .content {
            margin-left: 240px;
            padding: 25px;
            transition: margin-left 0.3s ease-in-out;
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
            transition: margin-left 0.3s ease-in-out;
        }

        .footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        /* Mobile Toggle & Overlay */
        .menu-toggle {
            display: none;
            font-size: 22px;
            background: none;
            border: none;
            cursor: pointer;
            color: #fff;
        }

        /* ADDED: Overlay for mobile view */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1010;
        }

        .overlay.active {
            display: block;
        }

        /* Responsive Styles (Mobile-first logic) */
        @media (max-width: 768px) {
            .menu-toggle {
                display: block;
            }

            .sidebar {
                top: 0;
                /* Cover full height on mobile */
                height: 100%;
                z-index: 1040;
                /* Above navbar and overlay */
                transform: translateX(-100%);
                /* CHANGED: Hide off-screen */
            }

            .sidebar.active {
                transform: translateX(0);
                /* CHANGED: Slide in */
            }

            .content,
            .footer {
                margin-left: 0;
            }
        }
    </style>
</head>

<body> <audio id="notification-sound" src="{{ asset('sounds/123.wav') }}" preload="auto"></audio>

    <div class="navbar">
        <div class="d-flex align-items-center">
            <button class="menu-toggle me-3" onclick="toggleSidebar()" aria-label="Toggle sidebar menu"
                aria-expanded="false" aria-controls="sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="brand">Hotel Manager</div>
        </div>

        <div class="d-flex align-items-center ms-auto gap-3">
            <div class="dropdown">
                <button class="btn btn-link text-white p-0" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false" style="font-size: 18px;">
                    <i class="fas fa-bell"></i>
                    <span id="notification-count"
                        class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 small rounded-circle"
                        style="display: none;"></span>
                </button>
                <div id="notificationDropdown" class="dropdown-menu dropdown-menu-end p-0"
                    style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('hotel-manager.profile.edit') }}"><i
                                class="fas fa-user me-2"></i>Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>
                                Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="{{ route('hotel-manager.dashboard') }}"
            class="{{ request()->routeIs('hotel-manager.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i>
            Dashboard</a>
        <a href="{{ route('hotel-manager.hotel.edit') }}"
            class="{{ request()->routeIs('hotel-manager.hotel.edit') ? 'active' : '' }}"><i class="fas fa-hotel"></i> My
            Hotel Details</a>
        <a href="{{ route('hotel-manager.rooms.index') }}"
            class="{{ request()->routeIs('hotel-manager.rooms.*') ? 'active' : '' }}"><i class="fas fa-bed"></i> Manage
            Rooms</a>
        <a href="{{ route('hotel-manager.guest-list.index') }}"
            class="{{ request()->routeIs('hotel-manager.guest-list.index') ? 'active' : '' }}"><i
                class="fas fa-book"></i> View Bookings</a>
        <a href="{{ route('hotel-manager.refund.index') }}"
            class="{{ request()->routeIs('hotel-manager.refund.index') ? 'active' : '' }}"><i
                class="fa-solid fa-money-check-dollar"></i> Refund</a>
        <a href="{{ route('hotel-manager.gallery.index') }}"
            class="{{ request()->routeIs('hotel-manager.gallery.index') ? 'active' : '' }}"><i
                class="fas fa-images"></i> Manage Gallery</a>
        <a href="{{ route('hotel-manager.revenue.index') }}"
            class="{{ request()->routeIs('hotel-manager.revenue.index') ? 'active' : '' }}"><i
                class="fas fa-dollar-sign"></i> Hotel Revenue</a>
        <a href="{{ route('hotel-manager.terms.edit') }}"
            class="{{ request()->routeIs('hotel-manager.terms.*') ? 'active' : '' }}"><i
                class="fas fa-file-contract"></i> Terms & Conditions</a>
    </div>

    <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    <script>
        // --- Sidebar Logic ---
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.querySelector('.menu-toggle');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            const isExpanded = sidebar.classList.contains('active');
            menuToggle.setAttribute('aria-expanded', isExpanded);
        }

        function closeSidebar() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            menuToggle.setAttribute('aria-expanded', 'false');
        }

        // --- Notification & Sound Logic ---
        const notificationCountEl = document.getElementById('notification-count');
        const notificationDropdownEl = document.getElementById('notificationDropdown');
        const notificationSound = document.getElementById('notification-sound');

        let previousNotificationCount = -1;

        function formatTimeAgo(dateString) {
            const now = new Date();
            const notificationDate = new Date(dateString);
            const secondsAgo = Math.round((now - notificationDate) / 1000);
            if (secondsAgo < 60) {
                return "a few seconds ago";
            }
            if (secondsAgo < 3600) {
                const minutes = Math.floor(secondsAgo / 60);
                return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
            }
            return notificationDate.toLocaleString('en-IN', {
                dateStyle: 'short',
                timeStyle: 'short'
            });
        }

        function fetchNotifications() {
            fetch('{{ route('hotel-manager.notifications.unread') }}')
                .then(response => response.json())
                .then(notifications => {
                    if (previousNotificationCount !== -1 && notifications.length > previousNotificationCount) {
                        playSound();
                    }
                    updateNotificationUI(notifications);
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        function playSound() {
            if (notificationSound) {
                notificationSound.currentTime = 0;
                const playPromise = notificationSound.play();
                if (playPromise !== undefined) {
                    playPromise.catch(error => {
                        console.error(
                            "Audio play was prevented by browser policy. User must interact with the page first.",
                            error);
                    });
                }
            }
        }

        function markAsRead(notificationId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = `/hotel-manager/notifications/${notificationId}/read`;
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
            }).then(response => {
                if (response.ok) fetchNotifications();
            });
        }

        function updateNotificationUI(notifications) {
            previousNotificationCount = notifications.length;

            if (notifications.length > 0) {
                notificationCountEl.innerText = notifications.length;
                notificationCountEl.style.display = 'block';
            } else {
                notificationCountEl.style.display = 'none';
            }

            let dropdownHtml = '<div class="p-2 fw-bold border-bottom">Notifications</div>';
            if (notifications.length === 0) {
                dropdownHtml += '<div class="p-3 text-center text-muted small">No new notifications</div>';
            } else {
                notifications.forEach(notification => {
                    const date = formatTimeAgo(notification.created_at);
                    dropdownHtml += `
                    <div class="p-2 border-bottom list-group-item-action">
                        <a href="${notification.data.url}" class="text-decoration-none text-dark d-block mb-1" style="font-size: 14px;">${notification.data.message}</a>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 12px;">${date}</span>
                            <button onclick="markAsRead('${notification.id}')" class="btn btn-sm btn-link text-primary p-0" style="font-size: 12px;">Mark as read</button>
                        </div>
                    </div>
                `;
                });
            }

            dropdownHtml +=
                `<a class="dropdown-item text-center fw-bold small p-2 bg-light border-top" href="{{ route('hotel-manager.notifications.all') }}">View all notifications</a>`;
            notificationDropdownEl.innerHTML = dropdownHtml;
        }

        // --- Main Event Listener ---
        document.addEventListener('DOMContentLoaded', function() {
            // This function "unlocks" the audio so it can be played later by the script
            function unlockAudio() {
                if (notificationSound && notificationSound.paused) {
                    // The key fix: Play and immediately pause the sound.
                    // This is a silent action that just gets browser permission.
                    notificationSound.play().catch(() => {});
                    notificationSound.pause();
                }
                // The second key fix: Remove the listener so this only ever runs ONCE.
                document.removeEventListener('click', unlockAudio);
                document.removeEventListener('touchstart', unlockAudio);
            }
            // Listen for the very first click or touch on the page
            document.addEventListener('click', unlockAudio);
            document.addEventListener('touchstart', unlockAudio);

            // Fetch the initial state of notifications when the page loads
            fetchNotifications();

            // Start polling for new notifications every 10 seconds
            setInterval(fetchNotifications, 10000);
        });
    </script>
    @stack('scripts')
</body>

</html>
