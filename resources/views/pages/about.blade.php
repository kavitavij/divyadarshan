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
        html {
            scroll-behavior: smooth;
        }
        [x-cloak] { display: none; }
    </style>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body id="top" class="bg-gray-50 text-gray-800 font-sans" x-data="{ loginModal: false, modalView: 'login' }">
    <!-- 🔹 Navbar -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <!-- Left: Logo -->
            <a href="/" class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition">
                DivyaDarshan
            </a>
            <!-- Middle: Menu -->
            <nav class="flex gap-6 text-sm font-medium text-gray-700 items-center">
                <a href="/" class="hover:text-blue-600">Home</a>
                <a href="/about" class="text-blue-600 font-semibold">About</a>
                <!-- Temples Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-1 hover:text-blue-600 focus:outline-none">
                        Temples
                        <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-transition
                        class="absolute bg-white border rounded shadow mt-2 min-w-max z-20">
                        @foreach ($allTemples as $temple)
                            <a href="{{ route('temples.show', $temple->id) }}"
                                class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap">
                                {{ $temple->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            <!-- Services Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-1 hover:text-blue-600 focus:outline-none">
                    Online Services
                    <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute bg-white border rounded shadow mt-2 min-w-max z-20">
                    <a href="{{ route('booking.index') }}" class="block px-4 py-2 hover:bg-gray-100">Darshan Booking</a>
                    <a href="{{ route('sevas.booking.index') }}" class="block px-4 py-2 hover:bg-gray-100">Sevas</a>
                    <a href="{{ route('stays.index') }}" class="block px-4 py-2 hover:bg-gray-100">Accommodation</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cab Booking</a>
                    <a href="{{ route('donations.index') }}" class="block px-4 py-2 hover:bg-gray-100">Donations</a>
                    <a href="{{ route('ebooks.index') }}" class="block px-4 py-2 hover:bg-gray-100">E-Books</a>
                </div>  
            </div>
                <!-- General Info Dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center gap-1 hover:text-blue-600 focus:outline-none">
                    General Info
                    <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false" x-transition
                    class="absolute bg-white border rounded shadow mt-2 min-w-max z-20">
                    <a href="{{ route('info.faq') }}" class="block px-4 py-2 hover:bg-gray-100">FAQs</a>
                    <a href="{{ route('info.sevas') }}" class="block px-4 py-2 hover:bg-gray-100">Sevas</a>
                    <a href="{{ route('info.dress-code') }}" class="block px-4 py-2 hover:bg-gray-100">Dress Code</a>
                    <a href="{{ route('info.contact') }}" class="block px-4 py-2 hover:bg-gray-100">Contact Us</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Privacy Policy</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-100">Cancellation Policy</a>
                </div>
            </div>
            </nav>
            <!-- Right: Auth -->
            <div>
                @guest
                    <button @click="loginModal = true; modalView = 'login'"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Login
                    </button>
                @else
                    <div class="relative group">
                        <button
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            class="absolute hidden group-hover:block right-0 bg-white border rounded shadow-lg mt-2 min-w-max z-20">
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('profile.ebooks') }}"
                                class="block px-4 py-2 hover:bg-gray-100">My eBooks</a>
                                <a href="{{ route ('profile.my-orders.index') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">My
                                   Orders</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="block px-4 py-2 hover:bg-gray-100">Log Out</a>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </header>
    <!-- 🔹 Content -->
    <main class="max-w-6xl mx-auto px-4 py-12 space-y-16">
        <!-- Quote -->
        <section class="text-center animate-fadeInUp">
            <h2 class="text-3xl md:text-4xl font-bold text-blue-800 mb-4">“सर्वे भवन्तु सुखिनः, सर्वे सन्तु
                निरामयाः।”</h2>
            <p class="text-gray-600">(May all be happy, may all be free from illness.)</p>
        </section>
        <!-- Mission -->
        <section class="text-center animate-fadeInUp">
            <h2 class="text-3xl font-bold text-blue-800 mb-4">🌟 Our Mission</h2>
            <p class="text-gray-700 max-w-2xl mx-auto">To connect every pilgrim with the divine — through technology,
                trust, and tradition.</p>
            <a href="#"
                class="mt-6 inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded shadow transition">Join
                Us</a>
        </section>
        <!-- Offerings -->
        <section class="bg-white p-6 rounded shadow-md animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 text-center">📦 What We Offer</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([['📅','Darshan Booking','Book online darshan slots at top temples.'],
                            ['🙏','Sevas & Poojas','Participate in temple rituals in-person or virtually.'],
                            ['🛌','Accommodation','Find temple rooms or nearby partner hotels.'],
                            ['📖','E-Books & Resources','Read holy texts and temple histories.'],
                            ['💰','Donations','Contribute to temples and receive 80G receipt.']] as $offer)
                    <div
                        class="bg-blue-50 p-6 rounded shadow hover:shadow-lg transition transform hover:-translate-y-1">
                        <div class="text-4xl mb-3">{{ $offer[0] }}</div>
                        <h3 class="text-lg font-semibold text-blue-700">{{ $offer[1] }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $offer[2] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
        <!-- Other Sections -->
        <section class="bg-blue-50 p-6 rounded animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-800 mb-2">🏛 History of Temples</h2>
            <p>India’s temples are not just spiritual centers — they are living legacies. Our platform showcases each
                temple’s history, origin stories, architecture, dress codes, and spiritual significance.</p>
        </section>
        <section class="bg-blue-50 p-6 rounded animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-800 mb-2">🔭 Our Vision</h2>
            <p>We envision a world where everyone — regardless of location — can access the blessings of sacred spaces,
                services, and knowledge.</p>
        </section>
        <section class="animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">🤝 Trusted Collaborations</h2>
            <p>We work with temple boards, travel partners, priests, and tech vendors to deliver an authentic and
                seamless experience.</p>
        </section>
        <section class="bg-white p-6 rounded shadow animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-800 mb-2">🧘 Community & Culture</h2>
            <p>We promote cultural festivals, bhajans, yoga, and knowledge-sharing — creating an inclusive space for
                spiritual growth.</p>
        </section>
        <section class="animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">💻 Technology Meets Tradition</h2>
            <p>From QR-coded darshan tickets to multilingual interfaces and secure payments — we combine innovation with
                devotion.</p>
        </section>
        @guest
            <section class="text-center py-12 animate-fadeInUp">
                <h2 class="text-3xl font-bold text-blue-700 mb-4">Start Your Divine Journey Today</h2>
                <p class="mb-6 text-gray-700">Register now and explore services across hundreds of Indian temples.</p>
                <a href="/register"
                    class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded shadow transition">Register
                    Now</a>
            </section>
        @endguest
    </main>
    <!-- Back to Top -->
    <a href="#top"
        class="fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition hidden md:block z-50">
        ↑
    </a>
    <!-- 🔹 Footer -->
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
<div x-show="loginModal" x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div @click.outside="loginModal = false"
        x-show="loginModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90"
        class="bg-white rounded-lg shadow-xl w-full max-w-md relative">
        <button @click="loginModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div x-show="modalView === 'login'" class="p-8">
            <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Login to your Account</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="login_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="login_email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="login_password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="login_password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between">
                    <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                </div>
                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Login</button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">
                Don't have an account?
                <button @click="modalView = 'register'" class="font-medium text-blue-600 hover:underline">Register here</button>
            </p>
        </div>
        <div x-show="modalView === 'register'" class="p-8" style="display: none;">
            <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">Create an Account</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                 <div>
                    <label for="register_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="register_name" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="register_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="register_email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="register_password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="register_password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                 <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">Register</button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-600 mt-6">
                Already have an account?
                <button @click="modalView = 'login'" class="font-medium text-blue-600 hover:underline">Login here</button>
            </p>
        </div>
    </div>
</div>
</body>
</html>