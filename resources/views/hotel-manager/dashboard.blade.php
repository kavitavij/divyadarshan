 @extends('layouts.hotel-manager')
 
@section('content')
<div class="container-fluid">
    <h1>Hotel Manager Dashboard</h1>

    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @else
        <p>Welcome, {{ Auth::user()->name }}!</p>

        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Your Hotel: <strong>{{ $hotel->name }}</strong></h3>
            </div>
            <div class="card-body">
                <p>{{ $hotel->description }}</p>
                <hr>
                <h5 class="card-title mt-4">Quick Actions</h5>
                
                <a href="{{ route('hotel-manager.hotel.edit') }}" class="btn btn-primary">Edit Hotel Details</a>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-info">Manage Rooms</a>
                <a href="{{ route('hotel-manager.bookings.index') }}" class="btn btn-success">View Bookings</a>
            </div>
        </div>
    @endif
</div>
@endsection
