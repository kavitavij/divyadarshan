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

<body x-data="appState()" x-init="initCart()" :class="{ 'overflow-hidden': loginModal || cartOpen }"
    class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-300">


    <script src="//unpkg.com/alpinejs" defer></script>

    <header class="bg-white dark:bg-gray-800 shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <!-- Left: Logo -->
            <div class="flex-shrink-0">
                <a href="/" class="text-2xl font-bold text-blue-600 dark:text-blue-400">DivyaDarshan</a>
            </div>

            <!-- Middle: Navigation -->
            <nav class="flex-1 flex justify-center items-center gap-8 text-sm font-medium">
                <a href="/"
                    class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">Home</a>
                <a href="/about"
                    class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">About</a>

                <!-- Temples Dropdown -->
                <div class="relative group">
                    <button
                        class="flex items-center gap-1 text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">
                        <span>Temples</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute hidden group-hover:block bg-white dark:bg-gray-700 border dark:border-gray-600 rounded shadow mt-1 min-w-max z-20">
                        @foreach ($allTemples as $temple)
                            <a href="{{ route('temples.show', $temple->id) }}"
                                class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 whitespace-nowrap">{{ $temple->name }}</a>
                        @endforeach
                    </div>
                </div>

                <!-- Online Services Dropdown -->
                <div class="relative group">
                    <button
                        class="flex items-center gap-1 text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">
                        <span>Online Services</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div
                        class="absolute hidden group-hover:block bg-white dark:bg-gray-700 border dark:border-gray-600 rounded shadow mt-1 min-w-max z-20">
                        <a href="{{ route('booking.index') }}"
                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 whitespace-nowrap">Darshan
                            Booking</a>
                        <a href="{{ route('sevas.booking.index') }}"
                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 whitespace-nowrap">Sevas</a>
                        <a href="{{ route('stays.index') }}"
                            class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 whitespace-nowrap">Accommodation
                            Booking</a>
                        {{-- <a href="#"
                class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                role="menuitem">Cab Booking</a> --}}
                    </div>
                </div>
                <div>
                    <a href="{{ route('ebooks.index') }}"
                        class="text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400">Ebooks</a>
                </div>
            </nav>
            <div class="flex-shrink-0 flex items-center gap-4">
                @guest
                    <button @click="loginModal = true"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Login
                    </button>
                @else
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div
                            class="absolute hidden group-hover:block right-0 bg-white border rounded shadow-lg mt-1 min-w-max z-20">
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('profile.ebooks') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My eBooks</a>
                            <a href="{{ route('profile.bookings') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My Bookings</a>
                            <a href='{{ route('cart.view') }}' class="block px-4 py-2 text-gray-700 hover:bg-gray-100">My
                                cart</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Log Out
                                </a>
                            </form>
                        </div>
                    </div>
                @endguest

                <!-- Cart Icon -->
                <a href="{{ route('cart.view') }}" class="relative">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-200" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
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
            </div>
        </div>
    </header>

    <!-- Swiper Slider -->
    <div class="flex justify-center mt-4">
        <div class="swiper mySwiper" style="max-width: 400px;">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple1.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;"
                        class="rounded" alt="Temple 1">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple2.jpg') }}" style="height: 150px; width: 100%; object-fit: cover;"
                        class="rounded" alt="Temple 2">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/temple3.jpg') }}"
                        style="height: 150px; width: 100%; object-fit: cover;" class="rounded" alt="Temple 3">
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

    </div>
    <!-- Cart Flyout Panel -->
    <div x-show="cartOpen" @click.away="cartOpen = false" class="fixed inset-0 z-50 overflow-hidden"
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
                            <a href="#"
                                class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Cart Flyout Panel -->
    <div x-show="cartOpen" @click.away="cartOpen = false" class="fixed inset-0 z-50 overflow-hidden"
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
                            <a href="#"
                                class="flex justify-center items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Login/Register/Forgot Password Modal -->
    <div x-show="loginModal" @click.away="loginModal = false"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-transition.opacity
        style="display: none;">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md" @keydown.escape.window="loginModal = false">

            <!-- Login Form -->
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
                        <div class="flex justify-between items-center mb-4">
                            <a href="#" class="text-blue-600 hover:underline text-sm"
                                @click.prevent="modalView = 'forgot'">Forgot Password?</a>
                            <a href="#" class="text-blue-600 hover:underline text-sm"
                                @click.prevent="modalView = 'register'">Click here to Register</a>
                        </div>
                        <a href="javascript:window.history.back();">GO Back </a>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Log In</button>
                        </div>
                    </form>
                </div>
            </template>

            <!-- Register Form -->
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
                        <div class="flex justify-between items-center mb-4">
                            <a href="#" class="text-blue-600 hover:underline text-sm"
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

            <!-- Forgot Password Form -->
            <template x-if="modalView === 'forgot'">
                <div>
                    <h2 class="text-xl font-bold mb-4">Forgot Password</h2>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                            <input id="email" name="email" type="email" required autofocus
                                class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <a href="#" class="text-blue-600 hover:underline text-sm"
                                @click.prevent="modalView = 'login'">Back to Login</a>
                            <a href="#" class="text-blue-600 hover:underline text-sm"
                                @click.prevent="modalView = 'register'">Click here to Register</a>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                                Send Password Reset Link
                            </button>
                        </div>
                    </form>
                </div>
            </template>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
    </script>
    <!--End of Tawk.to Script-->
    <script>
        const loginModal = document.getElementById('loginModal');
        const registerModal = document.getElementById('registerModal');

        document.getElementById('openLoginModal').addEventListener('click', () => {
            loginModal.classList.remove('hidden');
            loginModal.classList.add('flex');
        });

        document.getElementById('closeLoginModal').addEventListener('click', () => {
            loginModal.classList.remove('flex');
            loginModal.classList.add('hidden');
        });

        document.getElementById('closeRegisterModal').addEventListener('click', () => {
            registerModal.classList.remove('flex');
            registerModal.classList.add('hidden');
        });

        document.getElementById('showRegister').addEventListener('click', (e) => {
            e.preventDefault();
            loginModal.classList.remove('flex');
            loginModal.classList.add('hidden');
            registerModal.classList.remove('hidden');
            registerModal.classList.add('flex');
        });

        document.getElementById('showLogin').addEventListener('click', (e) => {
            e.preventDefault();
            registerModal.classList.remove('flex');
            registerModal.classList.add('hidden');
            loginModal.classList.remove('hidden');
            loginModal.classList.add('flex');
        });
    </script>
    <script>
        function appState() {
            return {
                // State for Modals
                loginModal: false,
                modalView: 'login', // Can be 'login', 'register', or 'forgot'

                // State for Cart
                cartOpen: false,
                cartItems: [],
                total: 0,

                // Initialize cart from localStorage
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
    {{-- 🔷 Swiper JS --}}
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
