@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Accommodation Summary</h2>
                    </div>
                    <div class="card-body">
                        <h3 class="text-xl font-semibold mb-3">Please confirm your stay details.</h3>

                        <div class="border rounded-lg p-4">
                            <p><strong>Hotel:</strong> {{ $stayBooking->room->hotel->name }}</p>
                            <p><strong>Room Type:</strong> {{ $stayBooking->room->type }}</p>
                            <p><strong>Check-in:</strong>
                                {{ \Carbon\Carbon::parse($stayBooking->check_in_date)->format('d M Y') }}</p>
                            <p><strong>Check-out:</strong>
                                {{ \Carbon\Carbon::parse($stayBooking->check_out_date)->format('d M Y') }}</p>
                            <p><strong>Guests:</strong> {{ $stayBooking->number_of_guests }}</p>
                            <p class="h4 mt-3"><strong>Total Amount:</strong>
                                ₹{{ number_format($stayBooking->total_amount, 2) }}</p>
                            <p><strong>Status:</strong> <span
                                    class="badge bg-warning text-dark">{{ $stayBooking->status }}</span></p>
                        </div>

                        <div class="text-center mt-4">
                            {{-- This will link to your universal payment page --}}
                            <a href="{{ route('payment.create', ['id' => $stayBooking->id, 'type' => 'stay']) }}"
                                class="btn btn-payment">Proceed to Payment</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
