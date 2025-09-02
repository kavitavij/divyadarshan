@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h2 class="text-2xl font-bold mb-6">Cancelled Booking #{{ $booking->id }}</h2>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Booking Information</h3>
        <p><strong>User:</strong> {{ $booking->user->name ?? 'N/A' }}</p>
        <p><strong>Temple:</strong> {{ $booking->temple->name ?? 'N/A' }}</p>
        <p><strong>Hotel:</strong> {{ $booking->hotel->name ?? 'N/A' }}</p>
        <p><strong>Booking Date:</strong> {{ $booking->booking_date->format('d M Y') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>

        <hr class="my-4">

        @if($booking->refundRequest)
    <h3 class="text-lg font-semibold mb-4">Refund Details</h3>
    <p><strong>Account Holder Name:</strong> {{ $booking->refundRequest->account_holder_name }}</p>
    <p><strong>Account Number:</strong> {{ $booking->refundRequest->account_number }}</p>
    <p><strong>IFSC Code:</strong> {{ $booking->refundRequest->ifsc_code }}</p>
    <p><strong>Bank Name:</strong> {{ $booking->refundRequest->bank_name }}</p>
    <p><strong>Refund Status:</strong>
        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
            {{ $booking->refundRequest->status == 'Successful' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
            {{ $booking->refundRequest->status }}
        </span>
    </p>

    @if($booking->refundRequest->status != 'Successful')
        <form action="{{ route('admin.booking-cancel.updateRefundStatus', $booking->id) }}" method="POST" class="mt-3">
            @csrf
            @method('PATCH')
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Mark as Successful
            </button>
        </form>
    @endif
@else
    <p class="text-gray-500">No refund request submitted for this booking.</p>
@endif
    </div>
</div>
@endsection
