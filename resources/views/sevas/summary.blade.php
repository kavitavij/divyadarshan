@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>Seva Booking Summary</h2></div>
                <div class="card-body">
                    <h3 class="text-xl font-semibold mb-3">Please confirm your Seva details before proceeding to payment.</h3>

                    <div class="border rounded-lg p-4">
                        <h4 class="font-bold">Seva Details</h4>
                        <hr class="my-2">
                        <p><strong>Temple:</strong> {{ $sevaBooking->seva->temple->name }}</p>
                        <p><strong>Seva:</strong> {{ $sevaBooking->seva->name }}</p>
                        <p><strong>Amount:</strong> ₹{{ number_format($sevaBooking->amount, 2) }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-warning text-dark">{{ $sevaBooking->status }}</span></p>
                    </div>
                    
                    <div class="text-center mt-4">
                        {{-- THE FIX: Using the correct variable and route --}}
                        <a href="{{ route('sevas.booking.payment', $sevaBooking) }}" class="btn btn-success btn-lg">Proceed to Payment</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
