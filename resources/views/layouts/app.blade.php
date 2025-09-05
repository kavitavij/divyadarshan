<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <title>DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" /> --}}
<style>
.half-screen-slider {
  position: relative;
  width: 100%;
  height: 50vh;
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
  align-items: center;
  justify-content: flex-start;
  position: relative;
}
.slide-content {
  margin-left: 60px;
  color: white;
}
.slide-content h1 {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}
.slide-content p {
  font-size: 1rem;
  margin-bottom: 1rem;
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
}

/* Curved Side */
.slider-curved-side {
  position: absolute;
  right: 0;
  top: 0;
  width: 150px;
  height: 100%;
  background: linear-gradient(to left, rgba(13,13,13,0.9), transparent);
  border-top-left-radius: 80% 50%;
  border-bottom-left-radius: 80% 50%;
  pointer-events: none;
}
</style>

</head>
<body x-data="appState()" x-init="initCart()"
    :class="{ 'overflow-hidden': loginModal || cartOpen || isMobileMenuOpen }"
    class="bg-gray-100 font-sans text-gray-800">

<header class="bg-[#0d0d0d] text-[#ccc] sticky top-0 z-50 font-poppins">
    <div class="max-w-[1200px] mx-auto flex items-center justify-between px-4 py-4">
        <!-- Logo on Left -->
        <div class="flex-shrink-0">
            <a href="/" class="text-yellow-400 font-bold text-2xl">DivyaDarshan</a>
        </div>

        <!-- Centered Navigation -->
        <nav class="hidden md:flex flex-1 justify-center items-center gap-6">
            <a href="/" class="hover:text-yellow-400 transition">Home</a>
            <a href="/about" class="hover:text-yellow-400 transition">About</a>

            <!-- Temples Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition">
                    Temples
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute mt-2 w-48 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40" style="display:none;">
                    @foreach ($allTemples as $temple)
                        <a href="{{ route('temples.show', $temple->id) }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">{{ $temple->name }}</a>
                    @endforeach
                </div>
            </div>

            <!-- Online Services Dropdown -->
            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition">
                    Online Services
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute mt-2 w-56 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40" style="display:none;">
                    <a href="{{ route('booking.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Darshan Booking</a>
                    <a href="{{ route('sevas.booking.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a>
                    <a href="{{ route('stays.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Accommodation Booking</a>
                    <a href="{{ route('donations.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Donations</a>
                </div>
            </div>

            <a href="{{ route('ebooks.index') }}" class="hover:text-yellow-400 transition">Ebooks</a>
        </nav>

        <!-- Right Side (Login + Cart) -->
        <div class="hidden md:flex items-center gap-4">
            @guest
                <button @click="loginModal = true" class="px-4 py-2 bg-yellow-500 text-[#0d0d0d] font-medium rounded hover:bg-yellow-400 transition">
                    Login
                </button>
            @else
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-[#0d0d0d] font-medium rounded hover:bg-yellow-400 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-[#1a1a1a] border border-[#333] rounded-md shadow-lg z-40" style="display:none;">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Admin Dashboard</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">Profile</a>
                        <a href="{{ route('profile.ebooks') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My ebooks</a>
                        <a href="{{ route('profile.my-orders.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-500 hover:text-[#0d0d0d]">Log Out</button>
                        </form>
                    </div>
                </div>
            @endguest

            <!-- Cart Icon -->
            <a href="{{ route('cart.view') }}" class="relative text-[#ccc] hover:text-yellow-400 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                @if (session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ count(session('cart')) }}</span>
                @endif
            </a>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center gap-4">
            <a href="{{ route('cart.view') }}" class="relative text-[#ccc] hover:text-yellow-400 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                @if (session('cart') && count(session('cart')) > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ count(session('cart')) }}</span>
                @endif
            </a>
            <button @click="isMobileMenuOpen = true" class="text-[#ccc] hover:text-yellow-400 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </div>
</header>

<!-- Slider Section -->
<div class="half-screen-slider">
  <div class="swiper mySwiper">
    <div class="swiper-wrapper">
      <div class="swiper-slide" style="background-image: url('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1lg1oxA8smD7QIE6GIe5FpnnPPgZdL4_WTg&s');">
        <div class="slide-content">
          <h1>“सर्वे भवन्तु सुखिनः, सर्वे सन्तु निरामयाः।”</h1>
          <p>May all be happy, may all be free from illness.</p>
          {{-- <button>Participate Now</button> --}}
        </div>
      </div>
     <div class="swiper-slide" style="background-image: url('https://www.shutterstock.com/image-photo/ganesh-illustration-colorful-hindu-lord-600nw-2344967115.jpg');">
        <div class="slide-content">
            <h1>Experience the Power of Sacred Rituals</h1>
            <p>Let the light guide your path to peace and purpose</p>
            <a href="{{ route('booking.index') }}">
            <button>Book Your Puja</button>
            </a>
        </div>
        </div>
      <div class="swiper-slide" style="background-image: url('https://www.shopperspointindia.com/cdn/shop/files/611b3hJzzKL._SL1500.jpg?v=1725436797&width=1445');">
        <div class="slide-content">
          <h1>Join Thousands in a Soulful Journey</h1>
          <p>Your devotion, our guidance</p>
          <button @click="spiritualHelpModal = true">Get Spiritual Help</button>
        </div>
      </div>
    </div>
    <div class="swiper-pagination"></div>

  </div>
  <!-- Curved Side -->
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
            {{-- @if (session('success'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif --}}
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

<footer style="background:#0d0d0d; color:#ccc; font-family:'Poppins', sans-serif; padding:60px 20px 30px;">

    <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:40px;">

        <!-- About -->
        <div>
            <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">About DivyaDarshan</h3>
            <p style="font-size:15px; line-height:1.6; color:#bbb;">
                Connecting devotees to divinity through online puja, darshan, seva, and temple services — anywhere, anytime.
                <a href="/about" style="color:#93e018; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">About Us</a></li>

            </p>
        </div>

        <!-- Quick Links -->
        <div>
            <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Quick Links</h3>
            <ul style="list-style:none; padding:0; margin:0;">
                <li><a href="/services" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Services</a></li>
                <li><a href="{{ route('reviews.index') }}" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Reviews</a></li>
                <li><a href="{{ route('guidelines') }}" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Guidelines</a></li>
                <li><a href="{{ route('complaint.form') }}" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Complaint</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div>
            <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Contact Us</h3>
            <p style="font-size:15px; color:#bbb; line-height:1.7;">
                📍 SOPL, Mohali, India <a href="{{ route('info.contact') }}" style="color:#93e018; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Contact</a>
                📞 +91 9876543210 <br>
                ✉️ <a href="mailto:support@divyadarshan.com" style="color:#facc15; text-decoration:none;">support@divyadarshan.com</a>
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
    <div style="text-align:center; margin-top:50px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.15); font-size:14px; color:#aaa;">
        © {{ date('Y') }} DivyaDarshan. All rights reserved. |
        <a href="{{ route('terms') }}" style="color:#facc15; text-decoration:none;">Terms & Conditions</a>
    </div>
</footer>


<div x-show="cartOpen" @click.away="cartOpen = false" class="fixed inset-0 z-50 overflow-hidden" style="display: none;">
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
<div x-show="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        <div @click.away="loginModal = false" class="bg-white text-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">

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
<div x-show="spiritualHelpModal" @keydown.escape.window="spiritualHelpModal = false"
    class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[999] p-4"
    x-cloak
    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <div @click.away="spiritualHelpModal = false"
         class="bg-[#1a1a1a] border border-yellow-500/20 text-gray-300 rounded-xl shadow-2xl shadow-yellow-500/5 w-full max-w-2xl max-h-[95vh] flex flex-col transform transition-all"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="flex items-center justify-between p-5 border-b border-gray-800">
            <div class="flex items-center gap-4">
                <i class="fas fa-praying-hands text-yellow-400 text-3xl"></i>
                <div>
                    <h2 class="text-2xl font-bold text-gray-100 font-serif">Spiritual Guidance</h2>
                    <p class="text-sm text-gray-500">We're here to help you on your journey.</p>
                </div>
            </div>
            <button @click="spiritualHelpModal = false" class="text-gray-500 hover:text-white transition rounded-full h-10 w-10 flex items-center justify-center hover:bg-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div class="p-6 md:p-8 overflow-y-auto">
            <form action="{{ route('spiritual-help.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                        <div class="relative">
                            <i class="fas fa-user absolute top-3.5 left-4 text-gray-500"></i>
                            <input type="text" name="name" id="name" required placeholder="Your full name"
                                   class="pl-11 w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                        </div>
                    </div>

                    <div>
                        <label for="contact_info" class="block text-sm font-medium text-gray-400 mb-1">Email / Phone</label>
                        <div class="relative">
                           <i class="fas fa-at absolute top-3.5 left-4 text-gray-500"></i>
                            <input type="text" name="contact_info" id="contact_info" required placeholder="Your contact details"
                                   class="pl-11 w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-400 mb-1">City</label>
                        <input type="text" name="city" id="city" required placeholder="e.g., Varanasi"
                               class="w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                    </div>

                    <div>
                        <label for="query_type" class="block text-sm font-medium text-gray-400 mb-1">Query Type</label>
                        <select name="query_type" id="query_type" required
                                class="w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            <option value="General Inquiry">General Inquiry</option>
                            <option value="Pooja Booking">Pooja Booking</option>
                            <option value="Astrology">Astrology</option>
                            <option value="Donation">Donation</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="temple_id" class="block text-sm font-medium text-gray-400 mb-1">For Which Temple (Optional)</label>
                        <select name="temple_id" id="temple_id"
                                class="w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            <option value="">Any Temple / General Question</option>
                            @foreach($allTemples as $temple)
                                <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="preferred_time" class="block text-sm font-medium text-gray-400 mb-1">Preferred Time to Contact</label>
                        <select name="preferred_time" id="preferred_time" required
                                class="w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                            <option>Morning (9am - 12pm)</option>
                            <option>Afternoon (12pm - 4pm)</option>
                            <option>Evening (4pm - 8pm)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-400 mb-1">Your Message/Query</label>
                        <textarea name="message" id="message" rows="5" required placeholder="Please describe your query in detail..."
                                  class="w-full bg-[#0d0d0d] border-gray-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition"></textarea>
                    </div>
                </div>

                <div class="mt-8 pt-5 border-t border-gray-800 flex justify-end gap-4">
                    <button type="button" @click="spiritualHelpModal = false"
                            class="px-6 py-3 bg-gray-800 text-gray-300 font-semibold rounded-lg hover:bg-gray-700 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-8 py-3 bg-yellow-500 text-black font-bold rounded-lg hover:bg-yellow-400 transition shadow-lg shadow-yellow-500/10">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
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
                isMobileMenuOpen: false,
                loginModal: false,
                modalView: 'login',
                spiritualHelpModal: false,
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
                        this.cartItems.push({ ...item, quantity: 1 });
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
       {{-- Swiper JS --}}

<script>
  const swiper = new Swiper(".mySwiper", {
    loop: true,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
  });
</script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.min.js" defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
     <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdR-7EGvRdTcL0NSvxG1pKan2bQu3nXuo&callback=initMap" async defer></script>
    @stack('scripts')

</body>
</html>
