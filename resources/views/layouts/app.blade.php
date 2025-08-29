<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body x-data="appState()" x-init="initCart()"
    :class="{ 'overflow-hidden': loginModal || cartOpen || isMobileMenuOpen }"
    class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-300">

    {{-- ▼▼▼ RESPONSIVE HEADER WITH CLICKABLE DROPDOWNS ▼▼▼ --}}
    <header x-data="{ isMobileMenuOpen: false }" class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-blue-600 dark:text-blue-400">DivyaDarshan</a>
                </div>

                <nav
                    class="hidden md:flex md:items-center md:space-x-8 text-sm font-medium text-gray-700 dark:text-gray-200">
                    <a href="/" class="hover:text-blue-600 dark:hover:text-blue-400">Home</a>
                    <a href="/about" class="hover:text-blue-600 dark:hover:text-blue-400">About</a>

                    {{-- ✅ Temples Dropdown (Click-based) --}}
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center gap-1 focus:outline-none">
                            <span>Temples</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute -left-4 mt-2 w-48 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md shadow-lg z-40"
                            style="display: none;">
                            @foreach ($allTemples as $temple)
                                <a href="{{ route('temples.show', $temple->id) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">{{ $temple->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    {{-- ✅ Online Services Dropdown (Click-based) --}}
                    <div x-data="{ open: false }" @click.away="open = false" class="relative">
                        <button @click="open = !open" class="flex items-center gap-1 focus:outline-none">
                            <span>Online Services</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute -left-4 mt-2 w-56 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md shadow-lg z-40"
                            style="display: none;">
                            <a href="{{ route('booking.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Darshan
                                Booking</a>
                            <a href="{{ route('sevas.booking.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Sevas</a>
                            <a href="{{ route('stays.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Accommodation
                                Booking</a>
                            <a href="{{ route ('donations.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">Donations</a>
                        </div>
                    </div>
                    <a href="{{ route('ebooks.index') }}"
                        class="hover:text-blue-600 dark:hover:text-blue-400">Ebooks</a>
                </nav>

                <div class="hidden md:flex items-center space-x-4">
                    @guest
                        <button @click="loginModal = true"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            Login
                        </button>
                    @else
                        {{-- ✅ User Profile Dropdown (Click-based) --}}
                        <div x-data="{ open: false }" @click.away="open = false" class="relative">
                            <button @click="open = !open"
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 border dark:border-gray-600 rounded-md shadow-lg z-40"
                                style="display: none;">
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Admin
                                        Dashboard</a>
                                @endif
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Profile</a>
                                <a href="{{ route('profile.ebooks') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">My
                                    eBooks</a>
                                {{-- <a href="{{ route('profile.my-bookings') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">My
                                    Bookings</a> --}}
                                {{-- <a href="{{ route ('profile.my-donations.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">My
                                   Donations</a> --}}
                                <a href="{{ route ('profile.my-orders.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">My
                                   Orders</a>
                                   <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    <a href="{{ route('cart.view') }}"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600">
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
                </div>

                {{-- Mobile menu button and cart --}}
                <div class="md:hidden flex items-center">
                    <a href="{{ route('cart.view') }}"
                        class="relative text-gray-600 dark:text-gray-300 hover:text-blue-600 mr-4">
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
                    <button @click="isMobileMenuOpen = true"
                        class="text-gray-700 dark:text-gray-200 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="isMobileMenuOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-x-full"
        x-transition:enter-end="opacity-100 transform translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-x-0"
        x-transition:leave-end="opacity-0 transform -translate-x-full"
        class="fixed inset-0 z-60 md:hidden"
        style="display: none;">

            <div @click="isMobileMenuOpen = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative w-64 h-full bg-white dark:bg-gray-800 p-4">
                <button @click="isMobileMenuOpen = false"
                    class="absolute top-4 right-4 text-gray-600 dark:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <nav class="mt-10 flex flex-col space-y-4 text-gray-700 dark:text-gray-200">
                    <a href="/" class="hover:text-blue-600">Home</a>
                    <a href="/about" class="hover:text-blue-600">About</a>

                    <h3 class="font-semibold text-gray-500 text-sm pt-2 border-t dark:border-gray-600">Temples</h3>
                    @foreach ($allTemples as $temple)
                        <a href="{{ route('temples.show', $temple->id) }}"
                            class="pl-4 hover:text-blue-600">{{ $temple->name }}</a>
                    @endforeach

                    <h3 class="font-semibold text-gray-500 text-sm pt-2 border-t dark:border-gray-600">Online Services
                    </h3>
                    <a href="{{ route('booking.index') }}" class="pl-4 hover:text-blue-600">Darshan Booking</a>
                    <a href="{{ route('sevas.booking.index') }}" class="pl-4 hover:text-blue-600">Sevas</a>
                    <a href="{{ route('stays.index') }}" class="pl-4 hover:text-blue-600">Accommodation Booking</a>

                    <a href="{{ route('ebooks.index') }}"
                        class="pt-2 border-t dark:border-gray-600 hover:text-blue-600">Ebooks</a>

                    <div class="pt-4 border-t dark:border-gray-600">
                        @guest
                            <a href="#" @click="loginModal = true; isMobileMenuOpen = false"
                                class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Login</a>
                        @else
                            <a href="{{ route('profile.my-bookings') }}" class="block mb-2 hover:text-blue-600">My
                                Bookings</a>
                            <a href="{{ route('profile.edit') }}" class="block mb-4 hover:text-blue-600">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left text-red-600 hover:bg-red-50 dark:hover:bg-gray-700 p-2 rounded-md">Log
                                    Out</button>
                            </form>
                        @endguest
                    </div>
                </nav>
            </div>
        </div>
    </header>
<div class="relative flex justify-center mt-4 z-10">
        <div class="swiper mySwiper" style="max-width: 400px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple1.jpg') }}"
                        style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 1">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple2.jpg') }}"
                        style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 2">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple3.jpg') }}"
                        style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 3">
                </div>
            </div>
        </div>
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
            @if (session('success'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <strong class="font-bold">Whoops! Something went wrong.</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <footer class="bg-gray-200 border-t py-4 text-sm text-gray-700">
        <div class="max-w-7xl mx-auto px-4 flex flex-wrap justify-between items-center gap-4">
            <div>&copy; {{ date('Y') }} DivyaDarshan. All rights reserved.</div>
            <div>
                Contact: +91-1234567890 |
                <a href="mailto:support@divyadarshan.com"
                    class="text-blue-600 hover:underline">support@divyadarshan.com</a>
            </div>
            <div>
                <a href="{{ route('terms') }}" class="text-blue-600 hover:underline">Terms & Condition</a> |
                <a href="{{ route('guidelines') }}" class="text-blue-600 hover:underline">Guidelines</a> |
                <a href="{{ route('complaint.form') }}" class="text-blue-600 hover:underline">Complaint</a>|
                <a href="/reviews" class="text-blue-600 hover:underline">Reviews</a>
            </div>
            <div class="flex gap-2">
                <a href="#" class="hover:text-blue-600">Facebook</a>
                <a href="#" class="hover:text-blue-600">Twitter</a>
                <a href="#" class="hover:text-blue-600">Instagram</a>
            </div>
        </div>
    </footer>

    <div x-show="cartOpen" @click.away="cartOpen = false"
     class="fixed inset-0 z-80 overflow-hidden"  <!-- ⬅ raised -->
     x-transition.opacity style="display: none;">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <section class="absolute inset-y-0 right-0 pl-10 max-w-full flex">
            <div class="w-screen max-w-md" x-show="cartOpen"
                x-transition:enter="transform transition ease-in-out duration-500"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
                <div class="h-full flex flex-col bg-white dark:bg-gray-800 shadow-xl overflow-y-scroll">
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
                                                        <p class="ml-4" x-text="`₹${item.price.toFixed(2)}`"></p>
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

    <div x-show="loginModal" @click.away="loginModal = false"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
      x-transition.opacity style="display: none;">
        <div class="bg-white dark:bg-gray-800 text-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md"
            @keydown.escape.window="loginModal = false">

            <template x-if="modalView === 'login'">
                <div>
                    <h2 class="text-xl font-bold mb-4">Login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" required autofocus
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 mb-1">Password</label>
                            <input id="password" name="password" type="password" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4 flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="mr-2">
                            <label for="remember" class="text-gray-700">Remember Me</label>
                        </div>
                        <div class="flex justify-between items-center mb-4 text-sm">
                            <a href="#" class="text-blue-600 hover:underline"
                                @click.prevent="modalView = 'forgot'">Forgot Password?</a>
                            <a href="#" class="text-blue-600 hover:underline"
                                @click.prevent="modalView = 'register'">Don't have an account? Register</a>
                        </div>
                        <a href="#" class="text-blue-600 hover:underline text-sm"
                            @click.prevent="loginModal = false">
                            Back to home
                        </a>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Log In</button>
                        </div>
                    </form>
                </div>
            </template>

            <template x-if="modalView === 'register'">
                <div>
                    <h2 class="text-xl font-bold mb-4">Register</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 mb-1">Name</label>
                            <input id="name" name="name" type="text" required autofocus
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-1">Email</label>
                            <input id="email" name="email" type="email" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 mb-1">Password</label>
                            <input id="password" name="password" type="password" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-gray-700 mb-1">Confirm
                                Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>

                        <div class="flex justify-between items-center mb-4 text-sm">
                            <a href="#" class="text-blue-600 hover:underline"
                                @click.prevent="modalView = 'login'">Already have an account? Login</a>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </template>

            <template x-if="modalView === 'forgot'">
                <div>
                    <h2 class="text-xl font-bold mb-4">Forgot Password</h2>

                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-md"
                            role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                        <p class="text-sm text-gray-600 mb-4">
                            No problem. Just let us know your email address and we will email you a password reset link.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                            <input id="email" name="email" type="email" required autofocus
                                :value="old('email')" class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="flex justify-between items-center mt-6">
                            <a href="#" class="text-blue-600 hover:underline text-sm"
                                @click.prevent="modalView = 'login'">Back to Login</a>
                            <button type="submit"
                                class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>

    @stack('scripts')
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
    </script>

    <script>
        function appState() {
            return {

                loginModal: false,
                modalView: 'login',

                cartOpen: false,
                cartItems: [],
                total: 0,

                initCart() {
                    this.cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
                    this.updateTotal();
                },
                addToCart(item) {
                    let existingItem = this.cartItems.find(i => i.id === item.id && i.type === item.type);
                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        this.cartItems.push({
                            ...item,
                            quantity: 1
                        });
                    }
                    this.saveCart();
                    this.cartOpen = true;
                },
                removeFromCart(index) {
                    this.cartItems.splice(index, 1);
                    this.saveCart();
                },
                saveCart() {
                    localStorage.setItem('cartItems', JSON.stringify(this.cartItems));
                    this.updateTotal();
                },
                updateTotal() {
                    this.total = this.cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                }
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
