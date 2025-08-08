@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-3xl font-bold text-blue-800 mb-4">{{ $temple->name }}</h1>
    <p class="text-gray-600 mb-6 text-lg">{{ $temple->location }}</p>

            <!-- 🖼️ Temple Image Slider -->
           @if ($temple->gallery)
            @php $images = json_decode($temple->gallery, true); @endphp
            <div class="slider-container mx-auto max-w-xl overflow-hidden relative rounded shadow mb-6">
                <div class="slider flex transition-transform duration-700 ease-in-out">
                    @foreach ($images as $img)
                        <img src="{{ asset('images/temples/' . $img) }}" class="slider-image" alt="Gallery image">
                    @endforeach
                </div>
            </div>
            @endif  



        <!-- 🛕 Temple Info Section -->
        <h2 class="text-xl font-semibold text-blue-700 mb-2">Temple Culture & Rituals</h2>
        <p class="text-gray-700">{{ $temple->culture }}</p>

        <!-- 🧾 History -->
        <h2 class="text-xl font-semibold text-blue-700 mt-6 mb-2">Temple History</h2>
        <p class="text-gray-700">{{ $temple->history }}</p>

        <!-- 📅 Best Time to Visit -->
        <h2 class="text-xl font-semibold text-blue-700 mt-6 mb-2">Best Time to Visit</h2>
        <p class="text-gray-700">{{ $temple->best_time }}</p>

        <!-- 📍 Location / Address -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-blue-700 mb-2">Address & Location</h2>
            <p class="text-gray-700 mb-3">{{ $temple->address }}</p>

           @if ($temple->map_embed)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-blue-700 mb-2">Location Map</h2>
                    {!! $temple->map_embed !!}
                </div>
            @endif

        </div>


        <!-- 📞 Contact -->
        <h2 class="text-xl font-semibold text-blue-700 mt-6 mb-2">Contact</h2>
        <p class="text-gray-700">
            📞 {{ $temple->phone }} <br>
            📧 {{ $temple->email }}
        </p>


    <!-- 🔙 Back Button -->
    <div class="mt-6">
        <a href="{{ route('home') }}" 
           class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
           ← Back to Temples
        </a>
    </div>
</div>
<script>
    let currentIndex = 0;
    const slider = document.querySelector('.slider');
    const images = document.querySelectorAll('.slider-image');

    setInterval(() => {
        currentIndex = (currentIndex + 1) % images.length;
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
    }, 3000); // change every 3 seconds
</script>

@endsection
