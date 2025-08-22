@extends('layouts.app')

@push('styles')
    <style>
        /* Style for the selected donation button */
        .donation-amount-btn.selected {
            background-color: #4a148c;
            /* Deep purple */
            color: white;
            border-color: #4a148c;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-bold text-gray-800 dark:text-white">Make a Donation</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                Your contribution helps us maintain the temples, support community services, and preserve our sacred
                heritage. Every donation, no matter how small, makes a significant impact.
            </p>
        </div>

        <div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">

            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- This is your main donation form --}}
            <form action="{{ route('donations.store') }}" method="POST">
                @csrf

                {{-- Field for selecting a temple --}}
                <div>
                    <label for="temple_id">Select Temple (Optional)</label>
                    <select name="temple_id" id="temple_id">
                        <option value="">General Donation</option>
                        @foreach ($temples as $temple)
                            <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Field for the amount --}}
                <div>
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter Amount" required>
                </div>

                {{-- The button now submits the form --}}
                <div class="mt-6">
                    <button type="submit">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountButtons = document.querySelectorAll('.donation-amount-btn');
            const customAmountInput = document.getElementById('custom-amount');

            amountButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const amount = this.dataset.amount;
                    customAmountInput.value = amount;
                    amountButtons.forEach(btn => btn.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            customAmountInput.addEventListener('input', function() {
                amountButtons.forEach(btn => btn.classList.remove('selected'));
            });
        });
    </script>
@endpush
