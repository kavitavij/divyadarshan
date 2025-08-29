@extends('layouts.app')

@push('styles')
<style>
    .donation-amount-btn.selected {
        background-color: #4a148c;
        color: white !important;
        border-color: #4a148c;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-10">

    {{-- Seva Information Section --}}
    <div class="max-w-4xl mx-auto mb-12 bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-center mb-8">🙏 Sevas & Offerings</h1>
        <p class="text-gray-700 mb-6 text-center">
            Participate in divine rituals and support our temples through Sevas and donations.
            Your contribution sustains the temple’s traditions and community services.
        </p>

        {{-- Daily Sevas --}}
        <div class="mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Daily Sevas</h3>
            <div class="space-y-3 text-gray-600">
                <div class="flex justify-between">
                    <span>Suprabhata Seva (Morning Chants)</span>
                    <span class="font-semibold">₹501</span>
                </div>
                <div class="flex justify-between">
                    <span>Archana (Special Prayers)</span>
                    <span class="font-semibold">₹1,101</span>
                </div>
                <div class="flex justify-between">
                    <span>Nitya Annadanam (Daily Food Offering)</span>
                    <span class="font-semibold">₹2,501</span>
                </div>
            </div>
        </div>

        {{-- Weekly Sevas --}}
        <div class="mb-8">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Weekly Sevas</h3>
            <div class="space-y-3 text-gray-600">
                <div class="flex justify-between">
                    <span>Abhishekam (Fridays)</span>
                    <span class="font-semibold">₹5,001</span>
                </div>
                <div class="flex justify-between">
                    <span>Vastra Seva (Clothing for Deities)</span>
                    <span class="font-semibold">₹7,501</span>
                </div>
            </div>
        </div>

        {{-- Special Sevas --}}
        <div>
            <h3 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Special Occasion Sevas</h3>
            <div class="space-y-3 text-gray-600">
                <div class="flex justify-between">
                    <span>Kalyanam (Celestial Wedding)</span>
                    <span class="font-semibold">₹15,001</span>
                </div>
                <div class="flex justify-between">
                    <span>Vahana Seva (Procession Ceremony)</span>
                    <span class="font-semibold">₹25,001</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Donation Section --}}
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg border">
        <h2 class="text-3xl font-extrabold text-center text-purple-700 mb-6">💰 Make a Donation</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('cart.addDonation') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Temple Selection --}}
            <div>
                <label for="temple_id" class="block text-gray-700 font-semibold mb-2">
                    Select Temple (Optional)
                </label>
                <select name="temple_id" id="temple_id"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">🙏 General Donation</option>
                    @foreach ($temples as $temple)
                        <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Donation Purpose (appears only if temple is selected) --}}
            <div id="donation-purpose-section" class="mt-6 hidden">
                <label for="donation_purpose" class="block text-gray-700 font-semibold mb-2">
                    Donation Purpose
                </label>
                <select name="donation_purpose" id="donation_purpose"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="">Select Purpose</option>
                    <option value="seva">🙏 Seva Sponsorship</option>
                    <option value="annadaan">🍛 Annadaan</option>
                    <option value="orphan">👶 Orphan Support</option>
                    <option value="maintenance">🏛️ Temple Maintenance</option>
                </select>
            </div>

            {{-- Donation Amount Buttons --}}
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Choose Amount</label>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                    <button type="button" data-amount="101" class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">₹101</button>
                    <button type="button" data-amount="501" class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">₹501</button>
                    <button type="button" data-amount="1101" class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">₹1,101</button>
                    <button type="button" data-amount="2000" class="donation-amount-btn border rounded-lg py-3 font-semibold hover:bg-purple-100">₹2,501</button>
                </div>
            </div>

            {{-- Custom Amount --}}
            <div>
                <label for="amount" class="block text-gray-700 font-semibold mb-2">
                    Or Enter Custom Amount
                </label>
                <input type="number" name="amount" id="custom-amount" placeholder="Enter Amount"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500"
                    required>
            </div>

            {{-- Submit --}}
            <div class="pt-4 text-center">
                <button type="submit" class="w-full px-6 py-3 bg-purple-700 text-white font-semibold rounded-lg shadow-md hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-300">
                    Add Donation to Cart
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
        const templeSelect = document.getElementById('temple_id');
        const donationPurposeSection = document.getElementById('donation-purpose-section');

        // Amount button selection
        amountButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const amount = this.dataset.amount;
                customAmountInput.value = amount;
                amountButtons.forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Clear when typing custom amount
        customAmountInput.addEventListener('input', () => {
            amountButtons.forEach(btn => btn.classList.remove('selected'));
        });

        // Show donation purpose when temple is selected
        templeSelect.addEventListener('change', function() {
            if (this.value) {
                donationPurposeSection.classList.remove('hidden');
            } else {
                donationPurposeSection.classList.add('hidden');
            }
        });
    });
</script>
@endpush
