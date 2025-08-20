@extends('layouts.hotel-manager')

@section('content')
<style>
    /* Dashboard Container */
    .dashboard-container {
        background: #f9f9ff;
        min-height: 100vh;
        padding: 20px;
    }

    /* Title */
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        /* color: #2c3e50; */
    }

    /* Welcome Text */
    .welcome-text {
        font-size: 1.1rem;
        color: #555;
        margin-bottom: 15px;
    }

    /* Card Styling */
    .hotel-card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s ease;
        border: none;
    }
    .hotel-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        /* background: linear-gradient(90deg, #4b6cb7, #182848); */
        color: black;
    }

    /* Description */
    .hotel-description {
        font-size: 1rem;
        color: #444;
    }

    /* Buttons */
    .btn {
        border-radius: 8px !important;
        padding: 10px 18px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn:hover {
        transform: scale(1.05);
    }

    /* Alerts */
    .alert {
        border-radius: 8px;
        font-weight: 500;
    }
</style>

<div class="dashboard-container container-fluid">

    <h1 class="dashboard-title mb-4">🏨 Hotel Manager Dashboard</h1>

    {{-- Success & Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    @if(isset($error))
        <div class="alert alert-danger shadow-sm">{{ $error }}</div>
    @else
        <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Manage your hotel with ease.</p>

        {{-- Hotel Card --}}
        <div class="card shadow-lg hotel-card mt-4">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    Your Hotel: <strong>{{ $hotel->name }}</strong>
                </h3>
            </div>
            <div class="card-body">
                <p class="hotel-description">{{ $hotel->description }}</p>
                <hr>
                <h5 class="mb-3">⚡ Quick Actions</h5>
                <div class="btn-group gap-2 flex-wrap">
                    <a href="{{ route('hotel-manager.hotel.edit') }}" class="btn btn-outline-primary">
                        ✏️ Edit Hotel Details
                    </a>
                    <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-outline-info">
                        🛏️ Manage Rooms
                    </a>
                    <a href="{{ route('hotel-manager.guest-list.index') }}" class="nav-link">
                        📖 View Bookings
                    </a>
                    <a href="{{ route('hotel-manager.guest-list.index') }}" class="nav-link">
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
