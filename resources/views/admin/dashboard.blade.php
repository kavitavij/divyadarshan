@extends('layouts.admin')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <!-- <p>This is your central dashboard. You can add widgets, stats, and quick links here.</p> -->
    <br>
    <br>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Quick Actions</h5>
            <a href="{{ route('admin.temples.index') }}" class="btn btn-info">Add New Temple</a>
            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-info">Manage Ebook</a>
            <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-info">Latest Updates</a>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-info">Manage Complaint</a>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-info">Bookings of darshan and sevas </a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-info">Manage Hotels</a><br>
            <br>
            <a href="{{ route('admin.contact-submissions.index') }}" class="btn btn-info">Contact Messages</a>
        </div>
    </div>
@endsection
