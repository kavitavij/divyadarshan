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

<body x-data="{ loginModal: false, modalView: 'login', mobileMenuOpen: false }" :class="{ 'overflow-hidden': loginModal || mobileMenuOpen }"
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
            <!-- Right: Login Button -->
            <div class="flex-shrink-0">
                @guest
                    <button id="openLoginModal" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Login
                    </button>
                    <div id="loginModal"
                        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md relative">
                            <button id="closeLoginModal"
                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                            <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-white mb-4">Login</h2>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" name="email" required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium">Password</label>
                                    <input id="password" type="password" name="password" required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div class="flex justify-between items-center">
                                    <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Login</button>
                                    <a href="#" id="showRegister" class="text-sm text-indigo-500 hover:underline">New
                                        user? Register</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="registerModal"
                        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md relative">
                            <button id="closeRegisterModal"
                                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                            <h2 class="text-xl font-semibold text-center text-gray-800 dark:text-white mb-4">Register</h2>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium">Name</label>
                                    <input id="name" type="text" name="name" required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" name="email" required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium">Password</label>
                                    <input id="password" type="password" name="password" required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-sm font-medium">Confirm
                                        Password</label>
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        required
                                        class="w-full mt-1 rounded border-gray-300 dark:bg-gray-700 dark:text-white" />
                                </div>

                                <div class="flex justify-between items-center">
                                    <button type="submit"
                                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Register</button>
                                    <a href="#" id="showLogin"
                                        class="text-sm text-indigo-500 hover:underline">Already registered?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- This dropdown shows only if the user is LOGGED IN --}}
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
            </div>

        </div>
        </div>
    </header>

    <!-- Swiper Slider -->
    <div class="flex justify-center mt-4">
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


</body>

</html>
