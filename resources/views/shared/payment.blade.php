@extends('layouts.app')

@push('styles')
<style>
    .payment-card {
        border: none;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
    }
    .payment-card .card-header {
        background-color: #4a148c; /* Deep purple */
        color: white;
        text-align: center;
        font-size: 1.5rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.25rem;
    }
    .payment-card .card-body {
        padding: 2rem;
    }
    .summary-box {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1.5rem;
    }
    .summary-box .total-amount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2c3e50;
    }
    .btn-pay-now {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
        font-size: 1.2rem;
        padding: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card payment-card">
                <div class="card-header"><h2>Complete Your Payment</h2></div>
                <div class="card-body">
                    <div class="alert alert-info text-center">
                        This is a mock payment gateway for demonstration purposes.
                    </div>

                    {{-- This section dynamically displays the correct summary --}}
                    <div class="mb-4 summary-box">
                        <h4 class="font-weight-bold">{{ $summary['title'] }}</h4>
                        <hr>
                        @foreach($summary['details'] as $key => $value)
                            <p><strong>{{ $key }}:</strong> {{ $value }}</p>
                        @endforeach
                        <p class="total-amount"><strong>Total Amount:</strong> ₹{{ number_format($summary['amount'], 2) }}</p>
                    </div>

                    {{-- This form will handle the final confirmation --}}
                    <form action="{{ $summary['confirm_route'] }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $summary['booking_id'] }}">
                        <input type="hidden" name="donation_id" value="{{ $summary['donation_id'] ?? null }}">
                        
                        {{-- Add mock card fields here if you wish --}}

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-3 btn-pay-now">Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
