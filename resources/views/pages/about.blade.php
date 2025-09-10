<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About DivyaDarshan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        body { font-family: 'Poppins', sans-serif; }
        .hero-section {
            height: 50vh; width: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://www.shutterstock.com/image-photo/grand-illustrated-indian-temple-background-260nw-2604158189.jpg');
            background-size: cover; background-position: center; background-repeat: no-repeat;
            display: flex; align-items: center; justify-content: center;
            color: white; text-align: center;
        }
        .hero-section h1 { font-size: 3rem; font-weight: 700; line-height: 1.2; margin-bottom: 1rem; }
        .hero-section p { font-size: 1.5rem; font-weight: 400; max-width: 800px; margin: 0 auto; }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body id="top" class="bg-white text-slate-800 font-sans">

{{--  Alpine.js logic to handle auto-opening the contact modal --}}
<div x-data="{
    loginModal: false,
    modalView: 'login',
    infoModalOpen: @json($openFaqModal ?? $openContactModal ?? false),
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
}" x-init="() => {
    // Automatically populate content if a modal should be open on page load
    if (infoModalOpen) {
        let contentType = @json(session('open_faq_modal') || ($errors->has('name') || $errors->has('email') || $errors->has('question'))) ? 'faq' : 'contact';
        const contentEl = document.querySelector(`#${contentType}-content`);
        if (contentEl) {
            modalTitle = contentEl.dataset.title;
            modalContent = contentEl.innerHTML;
        }
    }
}">

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
             {{-- <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-1 hover:text-yellow-400 transition focus:outline-none">
                    General Info <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition class="absolute bg-[#1a1a1a] border border-[#333] rounded shadow mt-2 min-w-max z-20" style="display: none;">
                      <a href="#" @click.prevent="showInfo('faq')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">FAQs</a>
                      <a href="#" @click.prevent="showInfo('sevas')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a>
                      <a href="#" @click.prevent="showInfo('dress-code')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Dress Code</a>
                      <a href="#" @click.prevent="showInfo('privacy')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Privacy Policy</a>
                      <a href="#" @click.prevent="showInfo('cancellation')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Cancellation Policy</a>
                      <a href="#" @click.prevent="showInfo('contact')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Contact Us</a>
                </div>
            </div> --}}
        </nav>
        {{-- Auth buttons --}}
        <div>
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
                            <a href="{{ route('cart.view') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My Cart</a>
                            <a href="{{ route('profile.ebooks') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My ebooks</a>
                            <a href="{{ route('profile.my-orders.index') }}" class="block px-4 py-2 text-sm text-[#ccc] hover:bg-yellow-500 hover:text-[#0d0d0d]">My Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-500 hover:text-[#0d0d0d]">Log Out</button>
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
            <img src="https://media.istockphoto.com/id/517188688/photo/mountain-landscape.jpg?s=1024x1024&w=0&k=20&c=z8_rWaI8x4zApNEEG9DnWlGXyDIXe-OmsAyQ5fGPVV8="
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
            <img src="https://media.istockphoto.com/id/814423752/photo/eye-of-model-with-colorful-art-make-up-close-up.jpg?s=612x612&w=0&k=20&c=l15OdMWjgCKycMMShP8UK94ELVlEGvt7GmB_esHWPYE="
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
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTs-EsR-sgUkYoaJ84q4t4bofokFETqGYtlhQ&s"
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
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSuUOmlLIqMj57HpPqDvCuEmJyXwIlcN-mxtA&s" alt="Co-Founder 1"
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

            {{-- <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">About DivyaDarshan</h3>
                <p style="font-size:15px; line-height:1.6; color:#bbb;">
                    Connecting devotees to divinity through online puja, darshan, seva, and temple services — anywhere, anytime.
                </p>
            </div> --}}

            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Quick Links</h3>
                <ul style="list-style:none; padding:0; margin:0;">
                    <li><a href="/services" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Services</a></li>
                    <li><a href="/reviews" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Reviews</a></li>
                    <li><a href="{{ route('guidelines') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Guidelines</a></li>
                    <li><a href="{{ route('complaint.form') }}" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Complaint</a></li>
                </ul>
            </div>
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">General Info</h3>
                <ul style="list-style:none; padding:0; margin:0;">
                    <li><a href="#" @click.prevent="showInfo('faq')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">FAQs</a></li>
                    <li><a href="#" @click.prevent="showInfo('sevas')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Sevas</a></li>
                    <li><a href="#" @click.prevent="showInfo('dress-code')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Dress Code</a></li>
                    <li><a href="#" @click.prevent="showInfo('privacy')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Privacy Policy</a></li>
                    <li><a href="#" @click.prevent="showInfo('cancellation')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Cancellation Policy</a></li>
                    <a href="#" @click.prevent="showInfo('contact')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Contact Us</a>
                </ul>
            </div>
            <div>
                <h3 style="color:#facc15; font-size:20px; font-weight:700; margin-bottom:15px;">Contact Us</h3>
                <p style="font-size:15px; color:#bbb; line-height:1.7;">
                    📍 SOPL, Mohali, India <a href="#" @click.prevent="showInfo('contact')" class="block px-4 py-2 hover:bg-yellow-500 hover:text-[#0d0d0d]">Contact Us</a>

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

     {{-- General Info Modal --}}
    <div x-show="infoModalOpen" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[999] p-4">
        <div @click.away="infoModalOpen = false" class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-2xl font-bold text-slate-800" x-text="modalTitle"></h2>
                <button @click="infoModalOpen = false" class="text-slate-500 hover:text-slate-800 text-3xl">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto prose max-w-none" x-html="modalContent">
                {{-- Content will be injected here by Alpine.js --}}
            </div>
        </div>
    </div>

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
{{-- Hidden Content for Modals --}}
<div class="hidden">
    <div class="hidden">
        {{-- The  FAQ template --}}
        <template id="faq-content" data-title="Frequently Asked Questions">
        <div class="space-y-4">
            {{-- Display existing FAQs from the database --}}
            @if($faqs->isNotEmpty())
                @foreach($faqs as $faq)
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $faq->question }}</h3>
                        <div class="text-gray-600 mt-2 prose max-w-none">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                @endforeach
            @else
                {{-- If no FAQs are in the database, show these default ones --}}
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-800">How do I book a darshan online?</h3>
                    <p class="text-gray-600 mt-2">
                        To book a darshan, go to the "Online Services" dropdown in the main menu and select "Darshan Booking". From there, choose your preferred temple and select an available date from the calendar to complete your booking.
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-800">What is the dress code for visiting the temples?</h3>
                    <p class="text-gray-600 mt-2">
                        All devotees are requested to wear modest and traditional attire that covers the shoulders and knees. For detailed guidelines, please refer to the "Dress Code" page under the "General Information" section.
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg text-gray-800">Can I view my past bookings?</h3>
                    <p class="text-gray-600 mt-2">
                        Yes. Once you're logged in, click on your name in the top-right corner and select "My Bookings" from the dropdown menu. This will show all your past and upcoming bookings.
                    </p>
                </div>
            @endif
        </div>

        {{-- Ask a Question Form --}}
        <div class="mt-8 pt-6 border-t">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Have a Question? Ask Us!</h2>
            <p class="text-gray-600 mb-4">
                If you have a question that isn’t listed above, feel free to reach out. Just provide your name, email, and your question using the form provided on the page. Our support team will get back to you as soon as possible.
            </p>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if($errors->has('name') || $errors->has('email') || $errors->has('question'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('info.faq.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Your Name</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Your Email</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2" value="{{ old('email') }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Your Question</label>
                    <textarea name="question" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>{{ old('question') }}</textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                        Submit Question
                    </button>
                </div>
            </form>
        </div>
    </template>

    <template id="sevas-content" data-title="About Sevas">
        {!! $settings['page_content_sevas'] ?? '<p>Sevas information is not available yet.</p>' !!}
        <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
        <p>For more enquiry, contact <a href="mailto:info@divyadarshan.com" style="color: #b45309; text-decoration: underline;">info@divyadarshan.com</a></p>
    </template>

    <template id="dress-code-content" data-title="Dress Code Guidelines">
        {!! $settings['page_content_dress_code'] ?? '<p>Dress code information is not available yet.</p>' !!}
        <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
        <p>For more enquiry, contact <a href="mailto:info@divyadarshan.com" style="color: #b45309; text-decoration: underline;">info@divyadarshan.com</a></p>
    </template>

    <template id="privacy-content" data-title="Privacy Policy">
        {!! $settings['page_content_privacy'] ?? '<p>Privacy Policy is not available yet.</p>' !!}
        <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
        <p>For more enquiry, contact <a href="mailto:info@divyadarshan.com" style="color: #b45309; text-decoration: underline;">info@divyadarshan.com</a></p>
    </template>

    <template id="cancellation-content" data-title="Cancellation Policy">
    {!! $settings['page_content_cancellation'] ?? '<p>Cancellation Policy information is not available yet.</p>' !!}
    <hr style="margin-top: 1.5rem; margin-bottom: 1.5rem;">
    <p>For more enquiry, contact <a href="mailto:info@divyadarshan.com" style="color: #b45309; text-decoration: underline;">info@divyadarshan.com</a></p>
    </template>

    <template id="contact-content" data-title="Contact Us">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Left side: Get in Touch info --}}
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Get in Touch</h3>
                    <p class="text-gray-600 mb-4">We are here to help you with any questions. Please feel free to reach out to us.</p>
                    <div class="space-y-3">
                        <p class="flex items-center text-gray-700">
                             <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>123 Sector 82 , Mohali, India</span>
                        </p>
                        <p class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:support@divyadarshan.com" class="text-blue-600 hover:underline">support@divyadarshan.com</a>
                        </p>
                         <p class="flex items-center text-gray-700">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>+91-1234567890</span>
                        </p>
                    </div>
                </div>

                {{-- Right side: The Form --}}
                <div>
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Please correct the errors below:</strong>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('info.contact.submit') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea name="message" id="message" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

    <template id="contact-content" data-title="Contact Us">
         {{-- This content is still static as requested --}}
         <p>For any inquiries or support, please reach out to us:</p>
         <ul>
             <li><strong>Email:</strong> <a href="mailto:support@divyadarshan.com">support@divyadarshan.com</a></li>
             <li><strong>Phone:</strong> +91 9876543210</li>
             <li><strong>Address:</strong> SOPL, Mohali, India</li>
         </ul>
    </template>
</div>
</body>
</html>
