@extends('layouts.app')

@push('styles')
<style>
    /* Style for the selected donation button */
    .donation-amount-btn.selected {
        background-color: #4a148c; /* Deep purple */
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
            Your contribution helps us maintain the temples, support community services, and preserve our sacred heritage. Every donation, no matter how small, makes a significant impact.
        </p>
    </div>

    <div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
        
        @if(session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('donations.store') }}" method="POST">
            @csrf

            {{-- Dropdown to select donation target --}}
            <div class="mb-6">
                <label for="temple_id" class="block text-lg font-medium text-gray-700 dark:text-gray-200">1. Choose where to donate</label>
                <select name="temple_id" id="temple_id" class="form-control mt-2">
                    <option value="">General Donation (for DivyaDarshan)</option>
                    @foreach($temples as $temple)
                        <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                    @endforeach
                </select>
            </div>

            <h3 class="text-2xl font-semibold mb-4 text-center">2. Choose an Amount</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <button type="button" class="donation-amount-btn p-4 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold" data-amount="101">₹101</button>
                <button type="button" class="donation-amount-btn p-4 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold" data-amount="251">₹251</button>
                <button type="button" class="donation-amount-btn p-4 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold" data-amount="501">₹501</button>
                <button type="button" class="donation-amount-btn p-4 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 font-semibold" data-amount="1101">₹1,101</button>
            </div>

            <div>
                <label for="custom-amount" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Or Enter a Custom Amount (INR)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                        <span class="text-gray-500 sm:text-sm">₹</span>
                    </div>
                    <input type="number" name="amount" id="custom-amount" class="form-control pl-7" placeholder="0.00" required>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700">
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
