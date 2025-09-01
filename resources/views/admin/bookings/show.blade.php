@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Booking Details #{{ $booking->id }}</h4>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to All Bookings
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Booking Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Temple:</strong> {{ $booking->temple->name }}</li>
                        <li class="list-group-item"><strong>Booked By:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</li>
                        <li class="list-group-item"><strong>Darshan Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }}</li>
                        <li class="list-group-item"><strong>Total Devotees:</strong> {{ $booking->number_of_people }}</li>
                        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-success">{{ Str::ucfirst($booking->status) }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5 mb-3">Devotee Details</h4>

    @foreach ($booking->devotees as $devotee)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Devotee: {{ $devotee->full_name }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th style="width: 150px;">Full Name:</th>
                                <td>{{ $devotee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Age / Gender:</th>
                                <td>{{ $devotee->age }} / {{ Str::ucfirst($devotee->gender) }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $devotee->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td>{{ $devotee->phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>
                                    {{ $devotee->address }}, <br>
                                    {{ $devotee->city }}, {{ $devotee->state }} - {{ $devotee->pincode }}
                                </td>
                            </tr>
                            <tr>
                                <th>ID Proof:</th>
                                <td><strong>{{ Str::upper($devotee->id_type) }}</strong> - {{ $devotee->id_number }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4 text-center">
                        <h6>ID Proof Photo</h6>
                        @if($devotee->id_photo_path)
                            <a href="{{ asset('storage/' . $devotee->id_photo_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $devotee->id_photo_path) }}" alt="ID of {{ $devotee->full_name }}" class="img-fluid rounded border" style="max-height: 200px;">
                            </a>
                            <small class="d-block mt-2 text-muted">(Click to view full size)</small>
                        @else
                            <p class="text-danger">No ID photo uploaded.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
