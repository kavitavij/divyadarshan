@extends('layouts.admin')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <!-- <p>This is your central dashboard. You can add widgets, stats, and quick links here.</p> -->
<br>
<br>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Quick Actions</h5>
            <a href="{{ route('admin.temples.index') }}" class="btn btn-primary">Add New Temple</a>
            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-primary">Upload Ebook</a>
            <a href="#" class="btn btn-primary">Manage Ebooks</a>
            <a href="#" class="btn btn-primary">Latest Updates</a>
            <a href="#" class="btn btn-primary">Manage Complaint</a>
        </div>
    </div>
@endsection