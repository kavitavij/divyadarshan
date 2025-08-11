<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-in-out both;
        }
    </style>
</head>
<body id="top" class="bg-gray-50 text-gray-800 font-sans">
<!--Navbar -->
<header class="bg-white shadow">
  <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
    
    <!-- Left: Logo -->
    <div class="flex-shrink-0">
      <h1 class="text-2xl font-bold text-blue-600">DivyaDarshan</h1>
    </div>

    <!-- Middle: Menu -->
    <nav class="flex gap-6 text-sm font-medium text-gray-700">
      <a href="/" class="hover:text-blue-600">Home</a>
      <a href="/about" class="text-blue-600 font-semibold">About</a>

      <!-- Temples Dropdown -->
<div class="relative group">
  <button 
    aria-haspopup="true" 
    aria-expanded="false" 
    class="cursor-pointer px-3 py-1 text-gray-700 hover:text-blue-600 font-semibold flex items-center gap-1 focus:outline-none focus:ring-2 focus:ring-blue-600 rounded"
    id="templesDropdownBtn"
  >
    Temples
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
      <path d="M19 9l-7 7-7-7"></path>
    </svg>
  </button>

  <div 
    class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
    role="menu" 
    aria-labelledby="templesDropdownBtn"
  >
    @foreach($allTemples as $temple)
      <a href="{{ route('temples.show', $temple->id) }}" 
         class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
         role="menuitem"
      >
        {{ $temple->name }}
      </a>
    @endforeach

    <hr class="my-1 border-gray-300">
    <a href="{{ route('temples.index') }}" class="block px-4 py-2 hover:bg-gray-100 font-semibold text-blue-600 whitespace-nowrap" role="menuitem">
      View All Temples
    </a>
  </div>
</div>




      <!-- Dropdown: Online Services -->
      <div class="relative group">
        <span class="cursor-pointer">Online Services</span>
        <div class="absolute z-10 hidden group-hover:block bg-white border rounded shadow mt-1 text-left">
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Darshan</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sevas</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Accommodation</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cab</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Donations</a>
        </div>
      </div>

      <!-- Dropdown: General Info -->
      <div class="relative group">
        <span class="cursor-pointer">General Information</span>
        <div class="absolute z-10 hidden group-hover:block bg-white border rounded shadow mt-1 text-left">
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">FAQs</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Sevas</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Dress Code</a>
          <a href="#" class="block px-4 py-2 hover:bg-gray-100">Contact Us</a>
        </div>
      </div>
    </nav>

    <!-- Right: Login -->
    <div class="flex-shrink-0">
      <a href="/login" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Login</a>
    </div>

  </div>
</header>


<main class="max-w-6xl mx-auto px-4 py-10 space-y-16">
    <section class="text-center animate-fadeInUp">
        <h2 class="text-3xl font-bold text-blue-800 mb-4 tracking-wide">“सर्वे भवन्तु सुखिनः, सर्वे सन्तु निरामयाः।”</h2>
        <p class="text-gray-700 mb-4">(May all be happy, may all be free from illness.)</p>
    </section>
    <section class="text-center animate-fadeInUp">
        <h2 class="text-3xl font-bold text-blue-800 mb-4 tracking-wide">🌟 Our Mission</h2>
        <p class="text-gray-700 mb-4">To connect every pilgrim with the divine — through technology, trust, and tradition.</p>
        <a href="" class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded shadow transition-all duration-300">Join Us</a>
    </section>
    <section class="bg-white p-6 rounded shadow-md animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-700 mb-6 tracking-wide text-center">📦 What We Offer</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
            @foreach([
                ['📅', 'Darshan Booking', 'Book online darshan slots at top temples.'],
                ['🙏', 'Sevas & Poojas', 'Participate in temple rituals in-person or virtually.'],
                ['🛌', 'Accommodation', 'Find temple rooms or nearby partner hotels.'],
                ['🚕', 'Cab Booking', 'Pre-book cabs for safe travel.'],
                ['📖', 'E-Books & Resources', 'Read holy texts and temple histories.'],
                ['💰', 'Donations', 'Contribute to temples and receive 80G receipt.']
            ] as $offer)
            <div class="bg-white p-6 rounded shadow text-center hover:shadow-md transition">
                <div class="text-4xl mb-2">{{ $offer[0] }}</div>
                <h3 class="text-lg font-semibold text-blue-700">{{ $offer[1] }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $offer[2] }}</p>
                
            </div>
            @endforeach
        </div>
    </section>
    <section class="animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-700 mb-3">🏛 History of Temples</h2>
        <p>India’s temples are not just spiritual centers — they are living legacies. Our platform showcases each temple’s history, origin stories, architecture, dress codes, and spiritual significance.</p>
    </section>
    <section class="bg-blue-50 p-6 rounded animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-800 mb-2">🔭 Our Vision</h2>
        <p>We envision a world where everyone — regardless of location — can access the blessings of sacred spaces, services, and knowledge.</p>
    </section>
    <section class="animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-700 mb-2">🤝 Trusted Collaborations</h2>
        <p>We work with temple boards, travel partners, priests, and tech vendors to deliver an authentic and seamless experience to all pilgrims.</p>
    </section>
    <section class="bg-white p-6 rounded shadow animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-800 mb-2">🧘 Community & Culture</h2>
        <p>We promote cultural festivals, bhajans, yoga, and knowledge-sharing — creating an inclusive space for spiritual growth.</p>
    </section>
    <section class="animate-fadeInUp">
        <h2 class="text-2xl font-bold text-blue-700 mb-2">💻 Technology Meets Tradition</h2>
        <p>From QR-coded darshan tickets to multilingual interfaces and secure payment gateways — we combine innovation with devotion.</p>
    </section>
    <section class="text-center py-10 animate-fadeInUp">
        <h2 class="text-3xl font-bold text-blue-700 mb-4">Start Your Divine Journey Today</h2>
        <p class="mb-6 text-gray-700">Register now and explore services across hundreds of Indian temples.</p>
        <a href="/register">Register Now</a>
    </section>

</main>
<a href="#top" 
   class="fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition hidden md:block z-50">
   ↑ Back to Top
</a>

<!-- 🔻 Footer -->
<footer class="bg-gray-200 text-center p-4 text-sm mt-10">
    &copy; {{ date('Y') }} DivyaDarshan. All rights reserved. <br>
    Contact: +91-1234567890 |
    <a href="mailto:support@divyadarshan.com" class="underline">support@divyadarshan.com</a> |
    <a href="#">Terms</a> | <a href="#">Guidelines</a> | <a href="#">Complaint</a>
</footer>
</body>
</html>
<style>
  html {
    scroll-behavior: smooth;
  }
</style>