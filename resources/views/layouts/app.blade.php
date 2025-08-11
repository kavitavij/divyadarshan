<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite([
        'resources/css/app.css',
        'resources/css/custom.css',
        'resources/js/app.js'
    ])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
</head>
<body x-data="{ loginModal: false, modalView: 'login' }" :class="{ 'overflow-hidden': loginModal }" class="bg-gray-100 font-sans text-gray-800">
<div class="max-w-7xl mx-auto px-4">
<script src="//unpkg.com/alpinejs" defer></script>

<header class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
    <!-- Left: Logo -->
    <div class="flex-shrink-0">
      <a href="/home" class="text-2xl font-bold text-blue-600">DivyaDarshan</a>
    </div>

    <!-- Middle: Navigation -->
    <nav class="flex items-center gap-8 text-sm font-medium text-gray-700">
      <a href="/home" class="hover:text-blue-600">Home</a>
      <a href="/about" class="hover:text-blue-600">About</a>

      <!-- Temples Dropdown -->
      <div class="relative group">
        <button 
          aria-haspopup="true" 
          aria-expanded="false" 
          class="inline-flex items-center gap-1 px-3 py-2 text-gray-700 hover:text-blue-600 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600 rounded"
          id="templesDropdownBtn">
          Temples
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <div 
          class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
          role="menu" 
          aria-labelledby="templesDropdownBtn">
          @foreach($allTemples as $temple)
            <a href="{{ route('temples.show', $temple->id) }}" 
              class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
              role="menuitem">
              {{ $temple->name }}
            </a>
          @endforeach
        </div>
      </div>

      <a href="#" class="hover:text-blue-600">Online Services</a>
      <a href="{{ route('ebooks') }}" class="hover:text-blue-600">eBooks</a>
    </nav>

    <!-- Right: Login Button -->
    <div class="flex-shrink-0">
      <button @click="loginModal = true; modalView = 'login'" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
  Login
</button>

    </div>
  </div>
</header>

<!-- Swiper Slider -->
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

<!-- Login/Register/Forgot Password Modal -->
<div 
    x-show="loginModal" 
    @click.away="loginModal = false" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    x-transition.opacity
    style="display: none;"
>
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
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'forgot'">Forgot Password?</a>
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'register'">Click here to Register</a>
              </div>
              <a href="javascript:window.history.back();">GO Back </a>
              <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Log In</button>
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
                  <label for="password_confirmation" class="block text-gray-700 mb-1">Confirm Password</label>
                  <input id="password_confirmation" name="password_confirmation" type="password" required
                      class="w-full border border-gray-300 rounded px-3 py-2">
              </div>
              <div class="flex justify-between items-center mb-4">
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'login'">Already have an account? Login</a>
              </div>
              <div class="flex justify-end">
                  <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
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
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'login'">Back to Login</a>
                  <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'register'">Click here to Register</a>
              </div>
              <div class="flex justify-end">
                  <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">
                      Send Password Reset Link
                  </button>
              </div>
          </form>
        </div>
      </template>

    </div>
</div>

</body>
</html>
