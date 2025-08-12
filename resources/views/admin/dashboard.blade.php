@extends('layouts.admin')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <p>This is your central dashboard. You can add widgets, stats, and quick links here.</p>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Quick Actions</h5>
            <a href="" class="btn btn-primary">Add New Temple</a>
            <a href="" class="btn btn-secondary">Upload Ebook</a>
        </div>
    </div>
@endsection