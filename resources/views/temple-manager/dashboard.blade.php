@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1 class="dashboard-title mb-4">Temple Manager Dashboard</h1>

        @if (session('success'))
            <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
        @endif

        @if (isset($error))
            <div class="alert alert-danger shadow-sm">{{ $error }}</div>
        @else
            <p class="welcome-text">Welcome, <strong>{{ Auth::user()->name }}</strong>! Manage your temple with ease.</p>

            <div class="card shadow-lg temple-card mt-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        Your Temple: <strong>{{ $temple->name }}</strong>
                    </h3>
                </div>
                <div class="card-body">
                    <p class="temple-description">{{ $temple->description }}</p>
                    <hr>
                    <h5 class="mb-3">⚡ Quick Actions</h5>
                    <div class="btn-group gap-2 flex-wrap">
                        <a href="{{ route('temple-manager.temple.edit') }}" class="btn btn-outline-primary">
                            ✏️ Edit Temple Details
                        </a>
                        <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-outline-info">
                            🗓️ Manage Darshan Slots
                        </a>
                        <a href="{{ route('temple-manager.sevas.index') }}" class="btn btn-outline-secondary">
                            🙏 Manage Sevas
                        </a>
                        {{-- THE FIX: This now links to the correct darshan bookings page --}}
                        <a href="{{ route('temple-manager.darshan-bookings.index') }}" class="btn btn-outline-success">
                            📖 View Darshan Bookings
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
