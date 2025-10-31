@extends('layouts.temple-manager')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                {{ $type === 'darshan' ? 'Darshan' : 'Seva' }} Booking Details (#{{ $booking->order->order_number ?? 'N/A' }})
            </h4>
            <a href="{{ route('temple-manager.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to All Bookings
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Booking Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Order Number:</strong> {{ $booking->order->order_number ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Booked By:</strong> {{ $booking->user->name }} ({{ $booking->user->email }})</li>

                        @if ($type === 'darshan')
                            <li class="list-group-item"><strong>Darshan Date:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F, Y') }}</li>
                            <li class="list-group-item"><strong>Time Slot:</strong> {{ $booking->slot_time }}</li>
                            <li class="list-group-item"><strong>Total Devotees:</strong> {{ $booking->number_of_people }}</li>
                        @elseif ($type === 'seva')
                            <li class="list-group-item"><strong>Seva Name:</strong> {{ $booking->seva->name }}</li>
                            <li class="list-group-item"><strong>Amount Paid:</strong> ₹{{ number_format($booking->amount, 2) }}</li>
                        @endif

                        <li class="list-group-item"><strong>Booked On:</strong> {{ $booking->created_at->format('d M Y, h:i A') }}</li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            @php
                                $statusClass = 'secondary';
                                if (strtolower($booking->status) === 'confirmed') $statusClass = 'success';
                                if (strtolower($booking->status) === 'cancelled') $statusClass = 'danger';
                                if (strtolower($booking->status) === 'completed') $statusClass = 'primary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }} text-capitalize">{{ $booking->status }}</span>
                        </li>
                    </ul>
                </div>
                <!-- <div class="col-md-6">
                    <h5>Update Status</h5>
                    <p class="text-muted">Change the status of this booking. The user will be notified of the change.</p>
                    <form action="{{ route('temple-manager.bookings.updateStatus', ['type' => $type, 'id' => $booking->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="input-group">
                            <select name="status" class="form-select">
                                <option value="Confirmed" {{ $booking->status === 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="Completed" {{ $booking->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ $booking->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div> -->
            </div>
        </div>
    </div>

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
                            <table class="table table-sm table-borderless">
                                <tr><th style="width: 150px;">Full Name:</th><td>{{ $devotee->full_name }}</td></tr>
                                <tr><th>Email:</th><td>{{ $devotee->email ?? 'N/A' }}</td></tr>
                                <tr><th>Phone:</th><td>{{ $devotee->phone_number ?? 'N/A' }}</td></tr>
                                <tr><th>Age / Gender:</th><td>{{ $devotee->age }} / {{ Str::ucfirst($devotee->gender) }}</td></tr>
                                <tr><th>ID Proof:</th><td><strong>{{ Str::upper($devotee->id_type) }}</strong> - {{ $devotee->id_number }}</td></tr>
                                <tr><th>Address:</th><td>{{ $devotee->address }}, <br>{{ $devotee->city }}, {{ $devotee->state }} - {{ $devotee->pincode }}</td></tr>
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

