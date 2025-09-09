@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Frequently Asked Questions (FAQs)</h1>

    <div class="max-w-3xl mx-auto space-y-4">
        {{-- Question 1 --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-gray-800">How do I book a darshan online?</h3>
            <p class="text-gray-600 mt-2">
                You can book a darshan by navigating to the "Online Services" dropdown in the main menu and selecting "Darshan Booking". From there, you can choose your desired temple and select an available date from the calendar to proceed with your booking.
            </p>
        </div>

        {{-- Question 2 --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-gray-800">What is the dress code for visiting the temples?</h3>
            <p class="text-gray-600 mt-2">
                We request all devotees to wear modest and traditional attire that covers the shoulders and knees. For specific guidelines, please visit the "Dress Code" page under the "General Information" menu.
            </p>
        </div>

        {{-- Question 3 --}}
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="font-semibold text-lg text-gray-800">Can I view my past bookings?</h3>
            <p class="text-gray-600 mt-2">
                Yes, once you are logged in, you can access all your past and upcoming bookings by clicking on your name in the top-right corner and selecting "My Bookings" from the dropdown menu.
            </p>
        </div>
    </div>

    {{-- Ask Your Question Form --}}
    <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Have a Question? Ask Us!</h2>

        <form action="#" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Your Name</label>
                <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Your Email</label>
                <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Your Question</label>
                <textarea name="question" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required></textarea>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Submit Question
            </button>
        </form>
    </div>
</div>
@endsection
