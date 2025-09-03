@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-2xl">
        <div x-data="refundForm()" class="bg-white shadow-lg rounded-xl p-8">
            <h1 class="text-3xl font-extrabold mb-4 text-gray-800 text-center">
                Accommodation Refund Request
            </h1>
            <p class="text-center text-gray-600 mb-8">
                Your booking for <strong>{{ $booking->room->hotel->name }}</strong> has been cancelled. Please provide your bank details below to process the refund.
            </p>

            <form action="{{ route('profile.my-stays.refund.store', $booking) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="account_holder_name" class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                    <input type="text" id="account_holder_name" name="account_holder_name" value="{{ old('account_holder_name', auth()->user()->name) }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    @error('account_holder_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                    <input type="text" id="account_number" name="account_number" value="{{ old('account_number') }}" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                     @error('account_number')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="ifsc_code" class="block text-sm font-medium text-gray-700">IFSC Code</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" x-model="ifsc" @input.debounce.500ms="fetchBankDetails" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" maxlength="11" placeholder="Enter 11-digit IFSC Code">
                    <p x-show="loading" class="mt-2 text-sm text-blue-500">Fetching bank details...</p>
                    <p x-show="error" x-text="error" class="mt-2 text-sm text-red-600"></p>
                    @error('ifsc_code')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name & Branch</label>
                    <input type="text" id="bank_name" name="bank_name" x-model="bankName" required readonly class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm">
                    @error('bank_name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit Refund Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Alpine.js script to fetch bank details from IFSC code --}}
<script>
    function refundForm() {
        return {
            ifsc: '{{ old('ifsc_code', '') }}',
            bankName: '{{ old('bank_name', '') }}',
            loading: false,
            error: '',
            fetchBankDetails() {
                this.bankName = ''; this.error = '';
                if (this.ifsc.length !== 11) {
                    if (this.ifsc.length > 0) this.error = 'IFSC code must be 11 characters.';
                    return;
                }
                this.loading = true;
                fetch(`https://ifsc.razorpay.com/${this.ifsc}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Invalid IFSC code.');
                        return response.json();
                    })
                    .then(data => { this.bankName = `${data.BANK} - ${data.BRANCH}`; })
                    .catch(error => { this.error = 'Could not find details for this IFSC code.'; })
                    .finally(() => { this.loading = false; });
            }
        }
    }
</script>
@endsection
