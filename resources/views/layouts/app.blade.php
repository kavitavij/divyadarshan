<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta name="google" content="notranslate">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <title>DivyaDarshan</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @stack('styles')
    <style>
        /* Page Fade-In Animation */
        body {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .half-screen-slider {
            position: relative;
            width: 100%;
            height: 55vh;
            overflow: hidden;
            border-radius: 30px;
        }

        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            position: relative;
        }

        .swiper-slide::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 60%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            pointer-events: none;
        }

        .slide-content {
            position: relative;
            z-index: 2;
            padding: 40px 60px;
            color: white;
            max-width: 600px;
        }

        .slide-content h1 {
            font-size: 2.25rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        .slide-content p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
        }

        .slide-content button {
            padding: 0.7rem 1.5rem;
            border: none;
            background-color: #facc15;
            color: #0d0d0d;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .slide-content button:hover {
            background-color: #eab308;
            transform: scale(1.05);
        }

        .translate-container {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        #google_translate_element .goog-logo-link,
        #google_translate_element .goog-te-gadget span {
            display: none !important;
        }

        #google_translate_element select {
            padding: 5px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background-color: #0d0d0d;
            color: #facc15;
            font-weight: 500;
            cursor: pointer;
            outline: none;
        }

        #google_translate_element select:hover {
            background-color: #1a1a1a;
        }

        .goog-te-gadget {
            font-size: 0;
        }

        @media (max-width: 768px) {
            .half-screen-slider {
                height: 50vh;
            }

            .slide-content {
                padding: 20px 25px;
            }

            .slide-content h1 {
                font-size: 1.75rem;
            }

            .slide-content p {
                font-size: 1rem;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body x-data="appState()" x-init="initCart()" :class="{ 'overflow-hidden': cartOpen || isMobileMenuOpen }"
    class="bg-gray-100 font-sans text-gray-800 dark:bg-gray-900">

    <header class="bg-[#910404fa] text-[#f1e8e8] sticky top-0 z-50 font-poppins">
        <div class="max-w-[1200px] mx-auto flex items-center justify-between px-4 py-4">

            <div class="flex-shrink-0">
                <a href="/" class="flex items-center gap-2 font-bold text-yellow-400">
                    <img src="{{ asset('images/logoo.png') }}" alt="DivyaDarshan Logo" class="h-14 w-14 object-contain">
                    <span class="text-2xl">DivyaDarshan</span>
                </a>
            </div>
            <nav class="hidden md:flex flex-1 justify-center items-center gap-6">
                <a href="/" class="hover:text-yellow-400 transition">Home</a>
                <a href="/about" class="hover:text-yellow-400 transition">About</a>

                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition">
                        Temples
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute mt-2 w-48 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40"
                        style="display:none;">
                        @foreach ($allTemples as $temple)
                            <a href="{{ route('temples.show', $temple->id) }}"
                                class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">{{ $temple->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition">
                        Online Services
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute mt-2 w-56 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40"
                        style="display:none;">
                        <a href="{{ route('booking.index') }}"
                            class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Darshan
                            Booking</a>
                        <a href="{{ route('sevas.booking.index') }}"
                            class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a>
                        <a href="{{ route('stays.index') }}"
                            class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Accommodation
                            Booking</a>
                        <a href="{{ route('donations.index') }}"
                            class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Donations</a>
                        <a href="{{ route('ebooks.index') }}"
                            class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Ebooks</a>
                    </div>
                </div>
            </nav>
            <div class="hidden md:flex items-center gap-4">
                <div class="translate-container flex items-center gap-2 ml-4">
                    <span class="globe-icon text-yellow-400 text-lg">Hi/En</span>
                    <div id="google_translate_element" class="inline-block"></div>
                </div>
                <button @click="spiritualHelpModal = true"
                    class="px-5 py-2 bg-white text-red-600 rounded-full font-semibold shadow hover:bg-red-600 hover:text-white transition">Get
                    Spiritual help</button>
                @guest
                    <a href="{{ route('login') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                        <img src="{{ asset('storage/logo/login.png') }}" alt="Login" class="h-10 w-auto">
                    </a>
                @else
                    {{-- NOTIFICATION BELL --}}
                    <div x-data="userNotificationBell()" x-init="init()" class="relative">
                        <button @click="isOpen = !isOpen" class="relative text-[#ccc] hover:text-yellow-400 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <template x-if="unreadCount > 0">
                                <span
                                    class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                                    x-text="unreadCount"></span>
                            </template>
                        </button>

                        {{-- UPDATED DROPDOWN PANEL --}}
                        <div x-show="isOpen" @click.away="isOpen = false" x-transition
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-50"
                            style="display:none;">
                            <div
                                class="p-3 font-bold border-b border-gray-200 dark:border-gray-700 text-gray-800 dark:text-white flex justify-between items-center">
                                <span>Notifications</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <template x-if="notifications.length === 0">
                                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">No new notifications
                                    </div>
                                </template>
                                <template x-for="n in notifications" :key="n.id">
                                    <div
                                        class="p-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <a :href="n.data.url"
                                            class="text-sm text-gray-700 dark:text-gray-300 block mb-1"
                                            x-text="n.data.message"></a>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500"
                                                x-text="formatTimeAgo(n.created_at)"></span>
                                            <button @click="markAsRead(n.id)"
                                                class="text-xs text-blue-500 hover:underline flex-shrink-0 ml-2">Mark
                                                as read</button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </div>
                    {{-- User Menu --}}
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-[#0d0d0d] font-medium rounded hover:bg-yellow-400 transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40"
                            style="display:none;">
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Admin
                                    Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Profile</a>
                            <a href="{{ route('cart.view') }}"
                                class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My
                                Cart</a>
                            <a href="{{ route('profile.ebooks') }}"
                                class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My
                                ebooks</a>
                            <a href="{{ route('profile.my-orders.index') }}"
                                class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My
                                Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-500 hover:text-[#0d0d0d]">Log
                                    Out</button>
                            </form>
                        </div>
                    </div>
                @endauth
                @auth
                    <a href="{{ route('cart.view') }}" class="relative text-[#ccc] hover:text-yellow-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        @if (session('cart') && count(session('cart')) > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                @endauth
            </div>
            {{-- MOBILE ACTION ICONS --}}
            <div class="flex items-center gap-4 md:hidden">
                @guest
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('storage/logo/login.png') }}" alt="Login" class="h-8 w-auto">
                    </a>
                @endguest
                @auth
                    <a href="{{ route('cart.view') }}" class="relative text-[#ccc] hover:text-yellow-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        @if (session('cart') && count(session('cart')) > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                @endauth
                <button @click="isMobileMenuOpen = true" class="text-[#ccc] hover:text-yellow-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    {{-- Mobile Responsive --}}
    <div x-show="isMobileMenuOpen" x-cloak class="fixed inset-0 z-[100] flex"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click="isMobileMenuOpen = false" class="fixed inset-0 bg-black/60" aria-hidden="true"></div>
        <div class="relative w-full max-w-xs bg-[#1a1a1a] text-[#f1e8e8] flex flex-col" x-show="isMobileMenuOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="p-4 flex items-center justify-between border-b border-gray-700">
                <a href="/" class="text-yellow-400 font-bold text-xl">DivyaDarshan</a>
                <button @click="isMobileMenuOpen = false" class="p-2">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex-grow p-4 space-y-2 text-lg">
                <a href="/" class="block px-3 py-2 rounded-md hover:bg-gray-700">Home</a>
                <a href="/about" class="block px-3 py-2 rounded-md hover:bg-gray-700">About</a>

                {{-- Mobile Dropdowns --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md hover:bg-gray-700">
                        <span>Temples</span>
                        <i class="fas fa-chevron-down text-sm transition-transform"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                        @foreach ($allTemples as $temple)
                            <a href="{{ route('temples.show', $temple->id) }}"
                                class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">{{ $temple->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full flex justify-between items-center px-3 py-2 rounded-md hover:bg-gray-700">
                        <span>Online Services</span>
                        <i class="fas fa-chevron-down text-sm transition-transform"
                            :class="{ 'rotate-180': open }"></i>
                    </button>
                    <div x-show="open" x-collapse class="pl-4 mt-1 space-y-1">
                        <a href="{{ route('booking.index') }}"
                            class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">Darshan Booking</a>
                        <a href="{{ route('sevas.booking.index') }}"
                            class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">Sevas</a>
                        <a href="{{ route('stays.index') }}"
                            class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">Accommodation</a>
                        <a href="{{ route('donations.index') }}"
                            class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">Donations</a>
                        <a href="{{ route('ebooks.index') }}"
                            class="block px-3 py-2 text-sm rounded-md hover:bg-gray-600">Ebooks</a>
                    </div>
                </div>
                {{-- USER ACCOUNT LINKS --}}
                @auth
                    <hr class="border-gray-700 my-2">
                    <div class="px-3 py-2 text-sm font-semibold text-gray-400 uppercase">My Account</div>
                    <a href="{{ route('cart.view') }}" class="block px-3 py-2 rounded-md hover:bg-gray-700">My
                        Cart</a>
                    <a href="{{ route('profile.ebooks') }}" class="block px-3 py-2 rounded-md hover:bg-gray-700">My
                        Ebooks</a>
                    <a href="{{ route('profile.my-orders.index') }}"
                        class="block px-3 py-2 rounded-md hover:bg-gray-700">My
                        Orders</a>
                @endauth
            </nav>
            <div class="p-4 border-t border-gray-700">
                @auth
                    <a href="{{ route('profile.edit') }}"
                        class="block text-center w-full px-4 py-3 bg-yellow-500 text-black font-bold rounded-md hover:bg-yellow-400">My
                        Profile</a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf
                        <button type="submit"
                            class="w-full text-center px-4 py-2 text-red-400 hover:bg-gray-700 rounded-md">Log
                            Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block text-center w-full px-4 py-3 bg-yellow-500 text-black font-bold rounded-md hover:bg-yellow-400">Login
                        / Register</a>
                @endauth
            </div>
        </div>
    </div>
    <div class="half-screen-slider">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide"
                    style="background-image: url('https://4kwallpapers.com/images/walls/thumbs_3t/266.jpg">
                    <div class="slide-content">
                        <h1>“सर्वे भवन्तु सुखिनः, सर्वे सन्तु निरामयाः।”</h1>
                        <p>May all be happy, may all be free from illness.</p>
                    </div>
                </div>
                <div class="swiper-slide"
                    style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/thumb/4/48/0052523_Kacheri_group_of_temples%2C_Dwarahat_Uttarakhand_251.jpg/2560px-0052523_Kacheri_group_of_temples%2C_Dwarahat_Uttarakhand_251.jpg">
                    <div class="slide-content">
                        <h1>Experience the Power of Sacred Rituals</h1>
                        <p>Let the light guide your path to peace and purpose</p>
                        <a href="{{ route('booking.index') }}">
                            <button>Book Your Darshan</button>
                        </a>
                    </div>
                </div>
                <div class="swiper-slide"
                    style="background-image: url('https://getwallpapers.com/wallpaper/full/c/2/d/633460.jpg">
                    <div class="slide-content">
                        <h1>Join Thousands in a Soulful Journey</h1>
                        <p>Your devotion, our guidance</p>
                        <button @click="spiritualHelpModal = true">Get Spiritual Help</button>
                    </div>
                </div>
                <div class="swiper-slide"
                    style="background-image: url('https://images.unsplash.com/photo-1517840901100-8179e982acb7?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aG90ZWwlMjBib29raW5nfGVufDB8fDB8fHww');">
                    <div class="slide-content">
                        <h1>Stay Close to the Divine</h1>
                        <p>Relax in our comfortable, well-appointed hotel rooms just steps from the temple.
                            Enjoy warm hospitality, modern amenities, and a serene atmosphere to make your
                            pilgrimage truly peaceful.</p>
                        <a href="{{ route('stays.index') }}">
                            <button>Explore Our Hotels</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="slider-curved-side"></div>
    </div>
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline font-medium">{{ session('status') }}</span>
                        <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
        </div>
        @yield('content')
    </main>
    <div x-show="infoModalOpen" x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[999] p-4" x-transition>
        <div @click.away="infoModalOpen = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-3xl max-h-[80vh] flex flex-col overflow-hidden">
            <div class="flex justify-between items-center bg-yellow-500 text-[#0d0d0d] px-6 py-4">
                <h2 class="text-2xl font-bold" x-text="modalTitle"></h2>
                <button @click="infoModalOpen = false" class="text-3xl font-bold hover:text-red-600">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto text-slate-700 prose max-w-none" x-html="modalContent"></div>
        </div>
    </div>
    {{-- Hidden Content Templates for Modals --}}

    <footer style="background:#910404fa; color:#ccc; font-family:'Poppins', sans-serif; padding:60px 20px 30px;">
        <div
            style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:40px;">
            <!-- About -->
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">About DivyaDarshan
                </h3>
                <p style="font-size:15px; line-height:1.6; color:#bbb;">
                    Connecting devotees to divinity through online puja, darshan, seva, and temple services —
                    anywhere, anytime.
                    <a href="/about"
                        style="color:#93e018; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">About
                        Us</a></li>
                </p>
            </div>
            <!-- Quick Links -->
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Quick Links</h3>
                <ul style="list-style:none; padding:0; margin:0;">
                    <li><a href="{{ route('reviews.index') }}"
                            style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Reviews</a>
                    </li>
                    <li><a href="{{ route('guidelines') }}"
                            style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Guidelines</a>
                    </li>
                    <li><a href="{{ route('complaint.form') }}"
                            style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Complaint</a>
                    </li>
                </ul>
            </div>
            <!-- Contact Info -->
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Contact Us</h3>
                <p style="font-size:15px; color:#bbb; line-height:1.7;">
                    📍 SOPL, Mohali, India<br>
                    📞 +91 9876543210 <br>
                    ✉️ <a href="mailto:support@divyadarshan.com"
                        style="color:#facc15; text-decoration:none;">support@divyadarshan.com</a>
                </p>
            </div>
            <!-- Social Media -->
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Follow Us</h3>
                <div style="display:flex; gap:15px; font-size:22px;">
                    <a href="#" style="color:#facc15; transition:0.3s;">🌐</a>
                    <a href="#" style="color:#facc15; transition:0.3s;">📘</a>
                    <a href="#" style="color:#facc15; transition:0.3s;">🐦</a>
                    <a href="#" style="color:#facc15; transition:0.3s;">📸</a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div
            style="text-align:center; margin-top:50px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.15); font-size:14px; color:#aaa;">
            © {{ date('Y') }} DivyaDarshan. All rights reserved. |
            <a href="{{ route('terms') }}" style="color:#facc15; text-decoration:none;">Terms & Conditions</a>
        </div>
    </footer>


    <div x-show="cartOpen" @click.away="cartOpen = false" class="fixed inset-0 z-50 overflow-hidden"
        style="display: none;">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75"></div>
        <section class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
            <div class="w-screen max-w-md" x-show="cartOpen" x-transition>
                <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                    <div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white">Shopping cart</h2>
                            <div class="ml-3 h-7 flex items-center">
                                <button @click="cartOpen = false" type="button"
                                    class="-m-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-8">
                            <div class="flow-root">
                                <ul role="list" class="-my-6 divide-y divide-gray-200 dark:divide-gray-700">
                                    <template x-for="(item, index) in cartItems" :key="index">
                                        <li class="py-6 flex">
                                            <div class="ml-4 flex-1 flex flex-col">
                                                <div>
                                                    <div
                                                        class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                                                        <h3 x-text="item.name"></h3>
                                                        <p class="ml-4" x-text="`₹${item.price.toFixed(2)}`">
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex-1 flex items-end justify-between text-sm">
                                                    <p class="text-gray-500 dark:text-gray-400"
                                                        x-text="`Qty ${item.quantity}`"></p>
                                                    <div class="flex">
                                                        <button @click="removeFromCart(index)" type="button"
                                                            class="font-medium text-indigo-600 hover:text-indigo-500">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="cartItems.length === 0"
                                        class="py-6 text-center text-gray-500 dark:text-gray-400">
                                        Your cart is empty.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div x-show="cartItems.length > 0"
                        class="border-t border-gray-200 dark:border-gray-700 py-6 px-4 sm:px-6">
                        <div class="flex justify-between text-base font-medium text-gray-900 dark:text-white">
                            <p>Subtotal</p>
                            <p x-text="`₹${total.toFixed(2)}`"></p>
                        </div>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Shipping and taxes calculated at
                            checkout.</p>
                        <div class="mt-6">
                            <a href="{{ route('cart.view') }}"
                                class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- Login modal removed: login page is used instead. --}}

    <div x-show="spiritualHelpModal" @keydown.escape.window="spiritualHelpModal = false"
        class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[999] p-4" x-cloak
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        <div @click.away="spiritualHelpModal = false"
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl max-h-[95vh] flex flex-col transform transition-all border border-gray-200 dark:border-gray-700">

            <div
                class="flex-shrink-0 flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-4">
                    <i class="fas fa-praying-hands text-yellow-500 text-3xl"></i>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 font-serif">Spiritual
                            Guidance</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">We're here to help you on your journey.
                        </p>
                    </div>
                </div>
                <button @click="spiritualHelpModal = false"
                    class="text-gray-400 hover:text-gray-700 dark:hover:text-white transition rounded-full h-10 w-10 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar">
                <form action="{{ route('spiritual-help.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="flex flex-col space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">Full
                                Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" name="name" id="name" required
                                    placeholder="Your full name"
                                    class="pl-10 py-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition" />
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label for="contact_info"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">Email
                                or Phone</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text" name="contact_info" id="contact_info" required
                                    placeholder="Your contact details"
                                    class="pl-10 py-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition" />
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label for="city"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" name="city" id="city" required
                                    placeholder="e.g., Varanasi"
                                    class="pl-10 py-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition" />
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label for="query_type" class="text-sm font-medium text-gray-700 dark:text-gray-300">Query
                                Type</label>
                            <select name="query_type" id="query_type" required
                                class="py-3 px-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Pooja Booking">Pooja Booking</option>
                                <option value="Astrology">Astrology</option>
                                <option value="Donation">Donation</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex flex-col space-y-2">
                            <label for="temple_id"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">Relevant
                                Temple
                                (Optional)</label>
                            <select name="temple_id" id="temple_id"
                                class="py-3 px-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                <option value="">Any Temple / General Question</option>
                                @if (isset($allTemples))
                                    @foreach ($allTemples as $temple)
                                        <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="md:col-span-2 flex flex-col space-y-2">
                            <label for="preferred_time"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300">Preferred Time to
                                Contact</label>
                            <select name="preferred_time" id="preferred_time" required
                                class="py-3 px-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                <option>Morning (9am - 12pm)</option>
                                <option>Afternoon (12pm - 4pm)</option>
                                <option>Evening (4pm - 8pm)</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 flex flex-col space-y-2">
                            <label for="message" class="text-sm font-medium text-gray-700 dark:text-gray-300">Your
                                Message or
                                Query</label>
                            <textarea name="message" id="message" rows="5" required
                                placeholder="Please describe your query in detail..."
                                class="p-3 w-full bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200 placeholder-gray-500 dark:placeholder-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"></textarea>
                        </div>
                    </div>

                    <div
                        class="flex-shrink-0 flex justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" @click="spiritualHelpModal = false"
                            class="px-6 py-2.5 bg-gray-200 dark:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-8 py-2.5 bg-yellow-500 text-black font-bold rounded-lg hover:bg-yellow-400 transition shadow-lg hover:shadow-yellow-500/20 transform hover:scale-105">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('styles')
        <style>
            .custom-scrollbar::-webkit-scrollbar {
                width: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: #f1f5f9;
                /* slate-100 */
            }

            .dark .custom-scrollbar::-webkit-scrollbar-track {
                background: #1e293b;
                /* slate-800 */
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #94a3b8;
                /* slate-400 */
                border-radius: 4px;
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb {
                background: #475569;
                /* slate-600 */
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #64748b;
                /* slate-500 */
            }

            .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #64748b;
                /* slate-500 */
            }
        </style>
    @endpush
    {{-- Swiper library --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.min.js" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script async defer src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdR-7EGvRdTcL0NSvxG1pKan2bQu3nXuo&callback=initMap" async
        defer></script>

    {{-- Alpine JS State --}}
    <script>
        function appState() {
            return {
                isMobileMenuOpen: false,
                spiritualHelpModal: false,
                cartOpen: false,
                cartItems: [],
                total: 0,
                initCart() {
                    this.cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
                    this.updateTotal && this.updateTotal();
                },
                infoModalOpen: @json(session('success_contact', false)),
                modalTitle: '',
                modalContent: '',
                showInfo(type) {
                    const contentEl = document.querySelector(`#${type}-content`);
                    if (contentEl) {
                        this.modalTitle = contentEl.dataset.title;
                        this.modalContent = contentEl.innerHTML;
                        this.infoModalOpen = true;
                    }
                }
            }
        }
    </script>
    @stack('scripts')
    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/68a2f658d8c4f11928198433/1j2u94ja5';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
        // End of Tawk.to Script
        function appState() {
            return {
                isMobileMenuOpen: false,
                spiritualHelpModal: false,
                cartOpen: false,
                cartItems: [],
                total: 0,
                initCart() {
                    /* ... */
                },
                // ... other functions
            }
        }

        // (NEW) USER NOTIFICATION SCRIPT
        function userNotificationBell() {
            return {
                isOpen: false,
                notifications: [],
                unreadCount: 0,
                formatTimeAgo(dateString) {
                    const now = new Date();
                    const notificationDate = new Date(dateString);
                    const secondsAgo = Math.round((now - notificationDate) / 1000);
                    if (secondsAgo < 60) {
                        return "a few seconds ago";
                    }
                    if (secondsAgo < 3600) {
                        const minutes = Math.floor(secondsAgo / 60);
                        return `${minutes} ${minutes === 1 ? 'minute' : 'minutes'} ago`;
                    }
                    return notificationDate.toLocaleString('en-IN', {
                        dateStyle: 'short',
                        timeStyle: 'short'
                    });
                },
                fetchNotifications() {
                    fetch('{{ route('notifications.index') }}')
                        .then(response => response.json())
                        .then(data => {
                            this.notifications = data;
                            this.unreadCount = data.length;
                        });
                },
                markAsRead(notificationId) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch(`/notifications/${notificationId}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                    }).then(response => {
                        if (response.ok) {
                            this.fetchNotifications();
                        }
                    });
                },
                init() {
                    this.fetchNotifications();
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 20000); // Check every 20 seconds
                }
            }
        }
    </script>
</body>

</html>
