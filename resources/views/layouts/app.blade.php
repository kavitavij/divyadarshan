<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ Load CSS & JS using Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/css/custom.css',
        'resources/js/app.js'
    ])

    {{-- Optional: External Swiper CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
</head>

<body x-data="{ loginModal: false }" class="bg-gray-100 font-sans text-gray-800">
<div class="max-w-7xl mx-auto px-4">

    {{-- 🔷 Banner --}}
    <div class="banner-wrapper">
        <img src="{{ asset('imagesa/banner.png') }}" alt="DivyaDarshan Banner">
        <div class="banner-logo-title">
            <img src="{{ asset('images/alogo.png') }}" alt="Logo">
            <h1>DivyaDarshan</h1>
        </div>
    </div>

    {{-- 🔷 Navigation --}}
    <nav class="flex flex-wrap justify-center gap-4 text-sm font-medium text-gray-700">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
        <a href="{{ route('about') }}" target="_blank" rel="noopener noreferrer">About</a>
        <a href="{{ route('temples.index') }}" class="hover:text-blue-600">Temples</a>
        <div class="relative group">
            <span class="cursor-pointer">Services ▾</span>
            <div class="absolute z-10 hidden group-hover:block bg-white border rounded shadow mt-1 text-left">
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Darshan</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sevas</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Accommodation</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cab</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Donations</a>
            </div>
        </div>
        <a href="{{ route('ebooks') }}" class="hover:text-blue-600">E-Books</a>
        <a href="#" class="hover:text-blue-600">Login</a>
    </div>

    {{-- 🔷 Swiper Slider --}}
    <div class="flex justify-center mt-4">
        <div class="swiper mySwiper" style="max-width: 400px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple1.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 1">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple2.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 2">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple3.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 3">
                </div>
            </div>
        </div>
    </div>

    {{-- 🔷 Page Content --}}
    <main class="py-10">
        @yield('content')
    </main>

    {{-- 🔷 Footer --}}
    <footer class="bg-gray-200 border-t py-4 text-sm text-gray-700">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-between items-center gap-4">
            <div>&copy; {{ date('Y') }} DivyaDarshan. All rights reserved.</div>
            <div>
                Contact: +91-1234567890 |
                <a href="mailto:support@divyadarshan.com" class="text-blue-600 hover:underline">support@divyadarshan.com</a>
            </div>
            <div>
                <a href="#" class="text-blue-600 hover:underline">Terms & Condition</a> |
                <a href="#" class="text-blue-600 hover:underline">Guidelines</a> |
                <a href="#" class="text-blue-600 hover:underline">Complaint</a>
            </div>
            <div class="flex gap-2">
                <a href="#" class="hover:text-blue-600">Facebook</a>
                <a href="#" class="hover:text-blue-600">Twitter</a>
                <a href="#" class="hover:text-blue-600">Instagram</a>
            </div>
        </div>
    </footer>

</div>

{{-- 🔷 Swiper JS --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.mySwiper', {
            loop: true,
            spaceBetween: 30,
            centeredSlides: true,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });
</script>
</body>
</html>
