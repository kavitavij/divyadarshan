@extends('layouts.admin')

@section('title', "Refund Request #{$refundRequest->id}")

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Refund Request Details</h4>
            <a href="{{ route('admin.booking-cancel.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                {{-- Bank Details --}}
                <div class="col-md-6 mb-4">
                    <h5>Bank Details for Refund</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Account Holder:</strong> {{ $refundRequest->account_holder_name }}</li>
                        <li class="list-group-item"><strong>Account Number:</strong> {{ $refundRequest->account_number }}</li>
                        <li class="list-group-item"><strong>IFSC Code:</strong> {{ $refundRequest->ifsc_code }}</li>
                        <li class="list-group-item"><strong>Bank Name:</strong> {{ $refundRequest->bank_name }}</li>
                    </ul>
                </div>

                {{-- Booking Details --}}
                <div class="col-md-6 mb-4">
                    <h5>Original Booking Details (ID: #{{ $refundRequest->bookingable->id }})</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Hotel:</strong> {{ $refundRequest->bookingable->room->hotel->name }}</li>
                        <li class="list-group-item"><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($refundRequest->bookingable->check_in_date)->format('d F, Y') }}</li>
                        <li class="list-group-item"><strong>Amount:</strong> &#8377;{{ number_format($refundRequest->bookingable->total_amount, 2) }}</li>
                        <li class="list-group-item"><strong>Booked By:</strong> {{ $refundRequest->bookingable->user->name }}</li>
                    </ul>
                </div>
            </div>

            <hr>

            {{-- Action Form --}}
            <div class="mt-4">
                <h5>Update Refund Status</h5>
                <p>Once the refund has been processed manually, update the status here.</p>
                <form action="{{ route('admin.booking-cancel.updateStatus', $refundRequest) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="Pending" {{ $refundRequest->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Successful" {{ $refundRequest->status === 'Successful' ? 'selected' : '' }}>Successful</option>
                                <option value="Failed" {{ $refundRequest->status === 'Failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success">Update Status</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
