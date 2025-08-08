<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | DivyaDarshan</title>
    @vite(['resources/css/custom.css'])
</head>
<body>

<div class="dashboard-container">
    <header class="dashboard-header">
        <h1>Welcome, {{ Auth::user()->name }}</h1>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </header>

    <main>
        <h2>📊 Admin Dashboard</h2>
        <div class="cards">
            <div class="card">Manage Temples</div>
            <div class="card">View E-Books</div>
            <div class="card">User Management</div>
            <div class="card">Site Settings</div>
        </div>
    </main>
</div>

</body>
</html>
