<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <header class="admin-header">
        <h1>DivyaDarshan Admin Panel</h1>
        <nav>
            <a href="{{ route('home') }}">🏠 View Site</a>
            <a href="#">📚 Manage E-Books</a>
            <a href="#">⛩️ Manage Temples</a>
            <a href="#">📅 Bookings</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
               🚪 Logout
            </a>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </header>

    <main class="admin-content">
        <div class="admin-card">
            <h2>Temples</h2>
            <p>Manage all temple details, images, and maps.</p>
            <a href="#">Go →</a>
        </div>

        <div class="admin-card">
            <h2>E-Books</h2>
            <p>Upload, edit, and remove publications.</p>
            <a href="#">Go →</a>
        </div>

        <div class="admin-card">
            <h2>Bookings</h2>
            <p>View darshan, seva, and accommodation bookings.</p>
            <a href="#">Go →</a>
        </div>
    </main>
</body>
</html>
