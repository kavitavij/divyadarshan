<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Temple Manager Dashboard</title>

    <link rel="icon" type="image/png" href="favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
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
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.2);
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
    <audio id="notification-sound" src="{{ asset('sounds/123.wav') }}" preload="auto"></audio>

    <nav class="navbar" role="navigation" aria-label="Main Navigation">
        <button class="menu-toggle" aria-label="Toggle sidebar menu" aria-expanded="false" aria-controls="sidebar"
            onclick="toggleSidebar(event)" onkeydown="if(event.key==='Enter') toggleSidebar(event)">☰</button>

        <div class="logo" aria-label="Temple Manager Dashboard">Temple Manager</div>

        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-link text-white p-0 position-relative" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false" style="font-size: 18px;">
                    <i class="fas fa-bell"></i>
                    <span id="notification-count"
                        class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 small rounded-circle"
                        style="display: none;"></span>
                </button>
                <div id="notificationDropdown" class="dropdown-menu dropdown-menu-end"
                    style="min-width: 320px; max-height: 400px; overflow-y: auto;">

                    <div class="p-3 text-center text-muted small">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="ms-2">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user-circle"></i> Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('temple-manager.profile.edit') }}"><i
                                class="fas fa-user me-2"></i>Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i
                                    class="fas fa-sign-out-alt me-2"></i>Logout</button>
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
    <div class="overlay" id="overlay" onclick="closeSidebar()" tabindex="-1" aria-hidden="true"></div>

    <main class="main" role="main">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"
        defer></script>
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

        // Use -1 to indicate the count hasn't been initialized yet
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
            fetch('{{ route('temple-manager.notifications.unread') }}')
                .then(response => response.json())
                .then(notifications => {
                    console.log('Fetched notifications:', notifications);
                    // Play sound only if the count has been initialized and is now greater
                    if (previousNotificationCount !== -1 && notifications.length > previousNotificationCount) {
                        playSound();
                    }
                    updateNotificationUI(notifications);
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        function playSound() {
            if (notificationSound) {
                notificationSound.currentTime = 0; // Rewind to the start
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
            const url = `/temple-manager/notifications/${notificationId}/read`;
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
            // Update the master count *before* rendering the UI
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
                `<a class="dropdown-item text-center fw-bold small p-2 bg-light" href="{{ route('temple-manager.notifications.all') }}">View all notifications</a>`;
            notificationDropdownEl.innerHTML = dropdownHtml;
        }

        // --- Main Event Listener ---
        document.addEventListener('DOMContentLoaded', function() {
            // This function "unlocks" the audio so it can be played later by the script.
            // Browsers block autoplay until the user interacts with the page.
            function unlockAudio() {
                if (notificationSound && notificationSound.paused) {
                    // A silent play-and-pause "unlocks" the audio context.
                    notificationSound.play().catch(() => {});
                    notificationSound.pause();
                }
                // Remove the listener so this only ever runs once.
                document.removeEventListener('click', unlockAudio);
                document.removeEventListener('touchstart', unlockAudio);
            }
            // Listen for the first user interaction.
            document.addEventListener('click', unlockAudio);
            document.addEventListener('touchstart', unlockAudio);

            fetchNotifications();

            // Start polling for new notifications every 10 seconds
            setInterval(fetchNotifications, 10000);
        });
    </script>

    @stack('scripts')
</body>
<div class="footer">
    &copy; {{ date('Y') }} <a href="/">DivyaDarshan</a>. All rights reserved.
</div>

</html>
