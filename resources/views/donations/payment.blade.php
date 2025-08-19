@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>Complete Your Donation</h2></div>
                <div class="card-body">
                    <div class="alert alert-info">
                        This is a mock payment gateway for demonstration purposes.
                    </div>

                    <div class="mb-4 border rounded p-3">
                        <p><strong>Donation Amount:</strong></p>
                        <p class="h4"><strong>₹{{ number_format($donation->amount, 2) }}</strong></p>
                        @if($donation->temple)
                            <p class="text-muted">For: {{ $donation->temple->name }}</p>
                        @else
                            <p class="text-muted">For: General DivyaDarshan Fund</p>
                        @endif
                    </div>

                    <form action="{{ route('donations.confirm') }}" method="POST">
                        @csrf
                        <input type="hidden" name="donation_id" value="{{ $donation->id }}">
                        
                        {{-- You can add mock card fields here if you wish --}}

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-3">Pay Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
