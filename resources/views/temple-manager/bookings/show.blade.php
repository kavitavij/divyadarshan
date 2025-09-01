@extends('layouts.temple-manager')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">{{ $type === 'darshan' ? 'Darshan' : 'Seva' }} Booking Details #{{ $booking->id }}</h4>
            <a href="{{ route('temple-manager.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to All Bookings
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Booking Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Booked By:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</li>

                        @if ($type === 'darshan')
                            <li class="list-group-item"><strong>Darshan Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }}</li>
                            <li class="list-group-item"><strong>Time Slot:</strong> {{ $booking->slot->time ?? 'N/A' }}</li>
                            <li class="list-group-item"><strong>Total Devotees:</strong> {{ $booking->number_of_people }}</li>
                        @elseif ($type === 'seva')
                            <li class="list-group-item"><strong>Seva Name:</strong> {{ $booking->seva->name }}</li>
                            <li class="list-group-item"><strong>Seva Date:</strong> {{ \Carbon\Carbon::parse($booking->seva_date)->format('d F, Y') }}</li>
                            <li class="list-group-item"><strong>Amount Paid:</strong> ₹{{ number_format($booking->seva->price, 2) }}</li>
                        @endif

                        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-success text-capitalize">{{ $booking->status }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Devotee Details will only show for Darshan Bookings --}}
    @if ($type === 'darshan' && $booking->devotees->isNotEmpty())
        <h4 class="mt-5 mb-3">Devotee Details</h4>
        @foreach ($booking->devotees as $devotee)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Devotee: {{ $devotee->full_name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            {{-- Devotee details table (same as admin view) --}}
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
                                    <th>ID Proof:</th>
                                    <td><strong>{{ Str::upper($devotee->id_type) }}</strong> - {{ $devotee->id_number }}</td>
                                </tr>
                                 <tr>
                                    <th>Address:</th>
                                    <td>
                                        {{ $devotee->address }}, <br>
                                        {{ $devotee->city }}, {{ $devotee->state }} - {{ $devotee->pincode }}
                                    </td>
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
    @endif
</div>
@endsection
