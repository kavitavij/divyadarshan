@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Sevas & Offerings</h1>
    
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <p class="text-gray-700 mb-6 text-center">
            Participate in the divine rituals and contribute to the temple's traditions by sponsoring a Seva. Your offering helps maintain the temple and supports its spiritual activities.
        </p>

        {{-- Daily Sevas Section --}}
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

        {{-- Weekly Sevas Section --}}
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

        {{-- Special Sevas Section --}}
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

        <div class="mt-8 text-center text-gray-500">
            <p>To book a Seva or for more information, please visit the temple office or use the "Contact Us" page.</p>
        </div>
    </div>
</div>
@endsection
