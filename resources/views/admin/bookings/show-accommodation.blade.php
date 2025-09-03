@extends('layouts.admin')

@section('title', "Accommodation Booking #{$booking->id}")

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Accommodation Booking #{{ $booking->id }}</h4>
            <a href="{{ route('admin.bookings.accommodation') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Accommodation Bookings
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Booking Information Section --}}
                <div class="col-md-6 mb-4">
                    <h5>Booking Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Hotel:</strong> {{ $booking->room->hotel->name ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Room Type:</strong> {{ $booking->room->type ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d F, Y') }}</li>
                        <li class="list-group-item"><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d F, Y') }}</li>
                        <li class="list-group-item"><strong>Total Guests:</strong> {{ $booking->number_of_guests }}</li>
                    </ul>
                </div>

                {{-- User Information Section --}}
                <div class="col-md-6 mb-4">
                    <h5>User Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Booked By:</strong> {{ $booking->user->name ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $booking->user->email ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Contact:</strong> {{ $booking->phone_number ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-success">{{ Str::ucfirst($booking->status) }}</span></li>
                        <li class="list-group-item"><strong>Booked On:</strong> {{ $booking->created_at->format('d F, Y, h:i A') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Guest Details Section --}}
    <h4 class="mt-5 mb-3">Guest Details</h4>

    @forelse ($booking->guests as $guest)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user me-2"></i>{{ $guest->name }}</h5>
                <p class="card-text mb-0"><strong>ID Proof:</strong> {{ Str::upper($guest->id_type) }} - {{ $guest->id_number }}</p>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No detailed guest information was recorded for this booking.</div>
    @endforelse
</div>
@endsection
