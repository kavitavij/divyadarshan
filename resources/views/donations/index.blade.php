@extends('layouts.app')

@push('styles')
    <style>
        /* Style for the selected donation button */
        .donation-amount-btn.selected {
            background-color: #4a148c;
            /* Deep purple */
            color: white !important;
            border-color: #4a148c;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl font-extrabold text-gray-800 dark:text-white">🙏 Make a Donation</h1>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                Your contribution helps us maintain the temples, support community services, and preserve our sacred
                heritage. Every donation, no matter how small, makes a significant impact.
            </p>
        </div>

        <div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border">
            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Donation Form --}}
            <form action="{{ route('donations.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Temple Selection --}}
                <div>
                    <label for="temple_id" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">
                        Select Temple (Optional)
                    </label>
                    <select name="temple_id" id="temple_id"
                        class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                        <option value="">🙏 General Donation</option>
                        @foreach ($temples as $temple)
                            <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Donation Amount Buttons --}}
                <div>
                    <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">Choose Amount</label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <button type="button" data-amount="100"
                            class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">
                            ₹100
                        </button>
                        <button type="button" data-amount="500"
                            class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">
                            ₹500
                        </button>
                        <button type="button" data-amount="1000"
                            class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">
                            ₹1000
                        </button>
                        <button type="button" data-amount="2000"
                            class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">
                            ₹2000
                        </button>
                    </div>
                </div>

                {{-- Custom Amount --}}
                <div>
                    <label for="amount" class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">
                        Or Enter Custom Amount
                    </label>
                    <input type="number" name="amount" id="custom-amount" placeholder="Enter Amount"
                        class="w-full border-gray-300 dark:border-gray-700 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                        required>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 text-center">
                    <button type="submit"
                        class="px-6 py-3 bg-purple-700 text-white font-bold rounded-lg shadow-md hover:bg-purple-800 transition">
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
                button.addEventListener('click', function(e) {
                    e.preventDefault();
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
