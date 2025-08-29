@extends('layouts.admin')

@push('styles')
{{-- Google Fonts --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Tiro+Devanagari+Sanskrit:wght@400;700&display=swap" rel="stylesheet">
{{-- Font Awesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f1f5f9;
    }
    .receipt-body {
        font-family: 'Tiro Devanagari Sanskrit', serif;
    }
    /* The primary print styles are now handled by the JavaScript function to ensure a clean print. */
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header with actions -->
    <header class="flex justify-between items-center mb-6 no-print">
        <a href="{{ route('admin.donations.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition">
            <i class="fas fa-arrow-left"></i>
            <span class="font-semibold">Back to Donations</span>
        </a>
    </header>

    <!-- Receipt Container -->
    <div id="printable-certificate" class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8">
        <!-- Header -->
        <div class="text-center border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 receipt-body">{{ $donation->temple->name ?? 'Divyadarshan Trust' }}</h1>
            <p class="text-gray-600">Donation Receipt</p>
        </div>

        <!-- Receipt Body -->
        <div class="receipt-body text-gray-700 space-y-4">
            <p><span class="font-semibold">Receipt No:</span> {{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p><span class="font-semibold">Date:</span> {{ $donation->created_at->format('d-M-Y') }}</p>
            <p><span class="font-semibold">Donor Name:</span> {{ $donation->user->name ?? 'A Valued Devotee' }}</p>
            <p><span class="font-semibold">Amount:</span> ₹ {{ number_format($donation->amount, 2) }}</p>
            @if($donation->purpose)
                <p><span class="font-semibold">Purpose:</span> {{ Illuminate\Support\Str::title(str_replace('_', ' ', $donation->purpose)) }}</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t pt-4 mt-6 text-center text-gray-500 text-sm">
            <p>Thank you for your generous support 🙏</p>
        </div>
    </div>
</div>
@endsection

