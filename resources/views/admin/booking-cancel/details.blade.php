@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h2 class="text-2xl font-bold mb-6">Booking Details #{{ $booking->id }}</h2>

    <!-- Booking Information -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4">Booking Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Temple:</strong> {{ $booking->temple->name ?? 'N/A' }}</p>
            <p><strong>Booked By:</strong> {{ $booking->user->name ?? 'N/A' }} ({{ $booking->user->email ?? 'N/A' }})</p>
            <p><strong>Darshan Date:</strong> {{ \Carbon\Carbon::parse($booking->darshan_date)->format('d F, Y') }}</p>
            <p><strong>Total Devotees:</strong> {{ $booking->total_devotees ?? 'N/A' }}</p>
            <p><strong>Status:</strong> <span class="text-red-600 font-semibold">Cancelled</span></p>
            <p><strong>Cancelled At:</strong> {{ $booking->cancelled_at ?? 'N/A' }}</p>
            <p><strong>Reason:</strong> {{ $booking->cancel_reason ?? 'Not provided' }}</p>
        </div>
    </div>

    <!-- Bank Details -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Bank Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p><strong>Account Holder Name:</strong> {{ $booking->bank->account_name ?? 'N/A' }}</p>
            <p><strong>Bank Name:</strong> {{ $booking->bank->bank_name ?? 'N/A' }}</p>
            <p><strong>Account Number:</strong> {{ $booking->bank->account_number ?? 'N/A' }}</p>
            <p><strong>IFSC Code:</strong> {{ $booking->bank->ifsc ?? 'N/A' }}</p>
            <p><strong>Refund Amount:</strong> ₹{{ $booking->refund_amount ?? '0.00' }}</p>
            <p><strong>Refund Status:</strong>
                <span class="{{ $booking->refund_status === 'Completed' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $booking->refund_status ?? 'Pending' }}
                </span>
            </p>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.booking-cancel.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            ← Back to Cancelled Bookings
        </a>
    </div>
</div>
@endsection
