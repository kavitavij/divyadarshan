<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Font Import --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-in-out both; }

        html { scroll-behavior: smooth; }

        [x-cloak] { display: none !important; }

        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-section {
            height: 50vh;
            width: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('https://www.shutterstock.com/image-photo/grand-illustrated-indian-temple-background-260nw-2604158189.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .hero-section h1 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .hero-section p {
            font-size: 1.5rem;
            font-weight: 400;
            max-width: 800px;
            margin: 0 auto;
        }
    </style>

    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body id="top" class="bg-white text-slate-800 font-sans" x-data="{ loginModal: false, modalView: 'login' }">

{{-- Header --}}
<header class="bg-[#0d0d0d] text-[#ccc] sticky top-0 z-50 font-poppins">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <a href="/" class="text-2xl font-bold text-yellow-400 hover:text-yellow-500 transition">
            DivyaDarshan
        </a>
        <nav class="hidden md:flex gap-6 text-sm font-medium text-[#ccc] items-center">
            <a href="/" class="hover:text-yellow-400 transition">Home</a>
            <a href="/about" class="hover:text-yellow-400 transition">About</a>
            {{-- Dropdowns --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition focus:outline-none">
                    Temples <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition class="absolute bg-[#1a1a1a] border border-[#333] rounded shadow mt-2 min-w-max z-20" style="display: none;">
                    @foreach ($allTemples as $temple)
                        <a href="{{ route('temples.show', $temple->id) }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">{{ $temple->name }}</a>
                    @endforeach
                </div>
            </div>
            <div x-data="{ open: false }" class="relative">
                 <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition focus:outline-none">
                    Online Services <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </button>
                 <div x-show="open" @click.outside="open = false" x-transition class="absolute bg-[#1a1a1a] border border-[#333] rounded shadow mt-2 min-w-max z-20" style="display: none;">
                    <a href="{{ route('booking.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Darshan Booking</a>
                    <a href="{{ route('sevas.booking.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a>
                    <a href="{{ route('stays.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Accommodation</a>
                    <a href="{{ route('donations.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Donations</a>
                    <a href="{{ route('ebooks.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">E-Books</a>
                </div>
            </div>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition focus:outline-none">
                    General Info <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition class="absolute bg-[#1a1a1a] border border-[#333] rounded shadow mt-2 min-w-max z-20" style="display: none;">
                     <a href="{{ route('info.faq') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">FAQs</a>
                     <a href="{{ route('info.sevas') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a>
                     <a href="{{ route('info.dress-code') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Dress Code</a>
                     <a href="{{ route('info.privacy') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Privacy Policy</a>
                     <a href="{{ route('info.cancellation') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Cancellation Policy</a>
                     <a href="{{ route('info.contact') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Contact Us</a>
                </div>
            </div>
        </nav>
        {{-- Auth buttons --}}
        <div>
            @guest
                <button @click="loginModal = true; modalView = 'login'" class="px-4 py-2 bg-yellow-500 text-[#0d0d0d] rounded hover:bg-yellow-400 transition">Login</button>
            @else
                <div class="relative group">
                    <button class="flex items-center gap-2 px-4 py-2 bg-yellow-500 text-[#0d0d0d] rounded hover:bg-yellow-400 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="absolute hidden group-hover:block right-0 bg-[#1a1a1a] border border-[#333] rounded shadow-lg mt-2 min-w-max z-20">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Admin Dashboard</a>
                        @endif
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Profile</a>
                        <a href="{{ route('profile.ebooks') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">My eBooks</a>
                        <a href="{{ route('profile.my-orders.index') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">My Orders</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 hover:bg-red-600 hover:text-[#0d0d0d]">Log Out</a>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>

<main>
    <section class="hero-section">
        <div>
            <h1>Bridging Hearts with Divinity</h1>
            <p>DivyaDarshan is a sacred bridge, connecting devotees worldwide...</p>
        </div>
    </section>

    <section class="py-16 lg:py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-fadeInUp">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Our Sacred Journey</h2>
                <p class="mt-4 text-slate-600 text-lg">
                    Born from a desire to make divine experiences accessible to all, DivyaDarshan was founded on the principles of authenticity, devotion, and service. We saw that distance should never be a barrier to faith. Our journey is to meticulously recreate the temple experience for you, no matter where you are, ensuring every ritual is performed with the sanctity it deserves.
                </p>
                <a href="{{ route('reviews.index') }}" class="mt-6 inline-block bg-yellow-500 text-slate-900 font-bold px-6 py-3 rounded-lg hover:bg-yellow-400 transition-colors duration-300">
                    See Devotee Stories
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 animate-fadeInUp" style="animation-delay: 0.2s;">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-slate-800">Our Mission</h3>
                    <p class="mt-2 text-slate-600">To connect every devotee with the divine through technology, trust, and tradition.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold text-slate-800">Our Vision</h3>
                    <p class="mt-2 text-slate-600">To create a world where everyone can access the blessings of sacred spaces and knowledge.</p>
                </div>
            </div>
        </div>
    </section>

<section class="py-16 lg:py-24">
         <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">The Path of Your Puja</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">From your Sankalp to Prasad delivery, we follow a transparent and sacred process.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-8 text-center">
                <div class="flex flex-col items-center animate-fadeInUp">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full text-2xl font-bold">1</div>
                    <h4 class="mt-4 font-semibold text-lg">Book a Seva</h4>
                    <p class="mt-1 text-slate-500 text-sm">Choose your desired Puja and provide your details.</p>
                </div>
                 <div class="flex flex-col items-center animate-fadeInUp" style="animation-delay: 0.1s;">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full text-2xl font-bold">2</div>
                    <h4 class="mt-4 font-semibold text-lg">Sankalp Taken</h4>
                    <p class="mt-1 text-slate-500 text-sm">A priest takes a vow using your name and gotra.</p>
                </div>
                 <div class="flex flex-col items-center animate-fadeInUp" style="animation-delay: 0.2s;">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full text-2xl font-bold">3</div>
                    <h4 class="mt-4 font-semibold text-lg">Puja Performed</h4>
                    <p class="mt-1 text-slate-500 text-sm">The ritual is performed authentically at the temple.</p>
                </div>
                 <div class="flex flex-col items-center animate-fadeInUp" style="animation-delay: 0.3s;">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full text-2xl font-bold">4</div>
                    <h4 class="mt-4 font-semibold text-lg">Receive Video</h4>
                    <p class="mt-1 text-slate-500 text-sm">A video of your Puja is sent to you via WhatsApp/Email.</p>
                </div>
                 <div class="flex flex-col items-center animate-fadeInUp" style="animation-delay: 0.4s;">
                    <div class="flex items-center justify-center w-16 h-16 bg-yellow-100 text-yellow-600 rounded-full text-2xl font-bold">5</div>
                    <h4 class="mt-4 font-semibold text-lg">Prasad Delivered</h4>
                    <p class="mt-1 text-slate-500 text-sm">Blessed Prasad is shipped from the temple to your home.</p>
                </div>
            </div>
        </div>
    </section>
        <section class="bg-slate-50 py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12 animate-fadeInUp">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Words of Devotion</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-3xl mx-auto">Hear from devotees who have completed their spiritual journey with us.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($reviews->take(3) as $review)
                    <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between transform hover:-translate-y-2 transition-transform duration-300 animate-fadeInUp">
                        {{-- THIS IS THE RESTORED CODE BLOCK --}}
                        <div class="flex-grow">
                            <div class="text-5xl text-yellow-500">“</div>
                            <p class="text-slate-600 mb-4 -mt-4 italic">"{{ $review->message }}"</p>
                        </div>
                        <div class="flex items-center justify-between mt-4">
                             <div class="flex items-center">
                                 <div class="flex-shrink-0">
                                     <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                                         <span class="text-xl font-bold">{{ substr($review->name, 0, 1) }}</span>
                                     </div>
                                 </div>
                                 <div class="ml-4">
                                     <div class="text-md font-semibold text-slate-900">{{ $review->name }}</div>
                                     <div class="flex items-center mt-1">
                                         @for ($i = 0; $i < 5; $i++)
                                             <svg class="w-4 h-4 {{ $i < $review->rating ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                         @endfor
                                     </div>
                                 </div>
                             </div>
                            <div class="flex items-center text-sm text-slate-500">
                                <button class="like-btn text-slate-400 hover:text-red-500 focus:outline-none focus:text-red-500 transition duration-150" data-review-id="{{ $review->id }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                                </button>
                                <span class="like-count ml-1 font-medium">{{ $review->likes ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-600 md:col-span-3 text-center">No reviews have been left yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Who We Are Section -->
    <section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Left side: text -->
        <div class="animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Who We Are</h2>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                At <span class="font-semibold text-yellow-600">DivyaDarshan</span>, we are dedicated to bridging the gap
                between tradition and technology. We strive to make divine experiences
                accessible to devotees across the globe by offering online temple services,
                pujas, and spiritual guidance, all rooted in authenticity and devotion.
            </p>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                Our mission is simple — to preserve the sanctity of age-old rituals while
                making them available to everyone, no matter where they are in the world.
                With every step, we honor the faith of millions and bring the blessings of
                temples closer to you.
            </p>
        </div>

        <!-- Right side: image -->
        <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
            <img src="-"
                alt="About DivyaDarshan"
                class="rounded-xl shadow-lg w-full object-cover">
        </div>

    </div>
    </section>

    <!-- Our Philosophy Section -->
    <section class="py-16 lg:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Left side: image -->
        <div class="animate-fadeInUp">
            <img src=""
                alt="Our Philosophy"
                class="rounded-xl shadow-lg w-full object-cover">
        </div>

        <!-- Right side: text -->
        <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Our Philosophy</h2>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                We believe that spirituality is a way of life guided by dharma, shaped by karma,
                and nurtured through daily acts of devotion. Each puja, each chadhawa,
                is a step closer to inner peace and divine connection.
            </p>
        </div>

    </div>
    </section>

    <!-- Culture We Cherish Section -->
    <section class="py-16 lg:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <!-- Left side: text -->
        <div class="animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Culture We Cherish</h2>
            <p class="mt-4 text-lg text-slate-600 leading-relaxed">
                From ancient temples to everyday homes, Hinduism lives through its rituals,
                festivals, and values. We aim to make these timeless traditions accessible
                and relevant in today's world.
            </p>
        </div>

        <!-- Right side: image -->
        <div class="animate-fadeInUp" style="animation-delay: 0.2s;">
            <img src=""
                alt="Culture We Cherish"
                class="rounded-xl shadow-lg w-full object-cover">
        </div>

    </div>
</section>
<!-- Our Co-Founders Section -->
<section class="py-16 bg-white text-gray-800">
  <div class="container mx-auto px-6 lg:px-16 text-center">
    <h2 class="text-4xl font-bold text-blue-700 mb-12">Our Co-Founders</h2>

    <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-3">
      <!-- Founder 1 -->
      <div class="flex flex-col items-center bg-gray-50 p-6 rounded-2xl shadow hover:shadow-lg transition">
        <img src="/images/founder1.jpg" alt="Co-Founder 1"
             class="w-32 h-32 rounded-full object-cover mb-4 shadow-md">
        <h3 class="text-xl font-semibold text-gray-900">ABCS</h3>
        <p class="text-sm text-gray-600 mb-3">Co-Founder & Visionary</p>
        <p class="text-gray-700 text-sm">
          Passionate about blending technology with spirituality to help devotees connect with temples worldwide.
        </p>
      </div>

      <!-- Founder 2 -->
      <div class="flex flex-col items-center bg-gray-50 p-6 rounded-2xl shadow hover:shadow-lg transition">
        <img src="/images/founder2.jpg" alt="Co-Founder 2"
             class="w-32 h-32 rounded-full object-cover mb-4 shadow-md">
        <h3 class="text-xl font-semibold text-gray-900">SOPL</h3>
        <p class="text-sm text-gray-600 mb-3">Co-Founder & Strategist</p>
        <p class="text-gray-700 text-sm">
          Focused on building meaningful cultural bridges and ensuring smooth spiritual experiences for users.
        </p>
      </div>

      <!-- Founder 3 (optional, remove if only 2 founders) -->
      <div class="flex flex-col items-center bg-gray-50 p-6 rounded-2xl shadow hover:shadow-lg transition">
        <img src="/images/founder3.jpg" alt="Co-Founder 3"
             class="w-32 h-32 rounded-full object-cover mb-4 shadow-md">
        <h3 class="text-xl font-semibold text-gray-900">////</h3>
        <p class="text-sm text-gray-600 mb-3">Tech & Operations</p>
        <p class="text-gray-700 text-sm">
          Ensuring robust technology, security, and smooth operations to support millions of devotees.
        </p>
      </div>
    </div>
  </div>
</section>


    <section class="py-16 lg:py-24">
        <div class="max-w-4xl mx-auto text-center px-4 animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900">Begin Your Divine Journey Today</h2>
            <p class="mt-4 text-lg text-slate-600">Explore sacred rituals, book a darshan, or connect with ancient temples. Your path to spiritual fulfillment is just a click away.</p>
            <div class="mt-8">
                @guest
                    <button @click="loginModal = true; modalView = 'register'" class="bg-yellow-500 text-slate-900 font-bold px-8 py-4 rounded-lg hover:bg-yellow-400 transition-colors duration-300">
                       Create an Account
                    </button>
                @else
                    <a href="/" class="bg-yellow-500 text-slate-900 font-bold px-8 py-4 rounded-lg hover:bg-yellow-400 transition-colors duration-300">
                       Explore Services
                    </a>
                @endguest
            </div>
        </div>
    </section>
</main>

<a href="#top"
   x-data="{ show: false }"
   x-show="show"
   x-init="window.addEventListener('scroll', () => show = window.scrollY > 300)"
   class="fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition hidden md:block z-50">
   ↑
</a>

<footer style="background:#0d0d0d; color:#ccc; font-family:'Poppins', sans-serif; padding:60px 20px 30px;">
        <div style="max-width:1200px; margin:0 auto; display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:40px;">

            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">About DivyaDarshan</h3>
                <p style="font-size:15px; line-height:1.6; color:#bbb;">
                    Connecting devotees to divinity through online puja, darshan, seva, and temple services — anywhere, anytime.
                </p>
            </div>

            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Quick Links</h3>
                <ul style="list-style:none; padding:0; margin:0;">
                    <li><a href="/services" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Services</a></li>
                    <li><a href="/reviews" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Reviews</a></li>
                    <li><a href="{{ route('guidelines') }}" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Guidelines</a></li>
                    <li><a href="{{ route('complaint.form') }}" style="color:#bbb; text-decoration:none; display:block; margin-bottom:10px;">Complaint</a></li>
                </ul>
            </div>

            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Contact Us</h3>
                <p style="font-size:15px; color:#bbb; line-height:1.7;">
                    📍 SOPL, Mohali, India <a href="{{ route('info.contact') }}" style="color:#93e018; text-decoration:none; display:block; margin-bottom:10px; transition:0.3s;">Contact</a>
                    📞 +91 9876543210 <br>
                    ✉️ <a href="mailto:support@divyadarshan.com" style="color:#facc15; text-decoration:none;">support@divyadarshan.com</a>
                </p>
            </div>

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

        <div style="text-align:center; margin-top:50px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.15); font-size:14px; color:#aaa;">
            © {{ date('Y') }} DivyaDarshan. All rights reserved. |
            <a href="{{ route('terms') }}" style="color:#facc15; text-decoration:none;">Terms & Conditions</a>
        </div>
    </footer>


{{-- EXACT LOGIN/REGISTER/FORGOT MODAL FROM YOUR LAYOUT FILE --}}
<div x-show="loginModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
    <div @click.away="loginModal = false" class="bg-white text-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md">

        <template x-if="modalView === 'login'">
            <div>
                <h2 class="text-xl font-bold mb-4">Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" required autofocus class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="mb-4 flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="mr-2">
                        <label for="remember" class="text-gray-700">Remember Me</label>
                    </div>
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <a href="#" class="text-blue-600 hover:underline" @click.prevent="modalView = 'forgot'">Forgot Password?</a>
                        <a href="#" class="text-blue-600 hover:underline" @click.prevent="modalView = 'register'">Don't have an account? Register</a>
                    </div>
                    <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="loginModal = false">
                        Back to home
                    </a>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Log In</button>
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
                        <input id="name" name="name" type="text" required autofocus class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1">Email</label>
                        <input id="email" name="email" type="email" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-gray-700 mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="flex justify-between items-center mb-4 text-sm">
                        <a href="#" class="text-blue-600 hover:underline" @click.prevent="modalView = 'login'">Already have an account? Login</a>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Register</button>
                    </div>
                </form>
            </div>
        </template>

        <template x-if="modalView === 'forgot'">
            <div>
                <h2 class="text-xl font-bold mb-4">Forgot Password</h2>
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-md" role="alert">
                        {{ session('status') }}
                    </div>
                @else
                    <p class="text-sm text-gray-600 mb-4">No problem. Just let us know your email address and we will email you a password reset link.</p>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 mb-1">Email Address</label>
                        <input id="email" name="email" type="email" required autofocus :value="old('email')" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div class="flex justify-between items-center mt-6">
                        <a href="#" class="text-blue-600 hover:underline text-sm" @click.prevent="modalView = 'login'">Back to Login</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Send Password Reset Link</button>
                    </div>
                </form>
            </div>
        </template>
    </div>
</div>

</body>
</html>
