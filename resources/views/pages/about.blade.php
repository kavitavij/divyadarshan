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
    </style>
</head>

<body id="top" class="bg-gray-50 text-gray-800 font-sans">

    <!-- Header -->
    <header x-data="{ isMobileMenuOpen: false }" class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-blue-600">DivyaDarshan</a>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex md:items-center md:space-x-6 text-sm font-medium text-gray-700">
                    <a href="/" class="hover:text-blue-600">Home</a>
                    <a href="/about" class="text-blue-600 font-semibold">About</a>

                    <!-- Temples Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 focus:outline-none">
                            <span>Temples</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute -left-4 mt-2 w-48 bg-white border rounded-md shadow-lg z-20">
                            @foreach ($allTemples as $temple)
                                <a href="{{ route('temples.show', $temple->id) }}"
                                    class="block px-4 py-2 text-sm hover:bg-gray-100">{{ $temple->name }}</a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Online Services Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 focus:outline-none">
                            <span>Online Services</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute -left-4 mt-2 w-48 bg-white border rounded-md shadow-lg z-20">
                            <a href="{{ route('booking.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">Darshan Booking</a>
                            <a href="{{ route('sevas.booking.index') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">Sevas</a>
                            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100">Accommodation Booking</a>
                        </div>
                    </div>

                    <!-- General Info Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.outside="open = false"
                            class="flex items-center gap-1 focus:outline-none">
                            <span>General Info</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute -left-4 mt-2 w-48 bg-white border rounded-md shadow-lg z-20">
                            <a href="{{ route('info.sevas') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">Seva Info</a>
                            <a href="{{ route('info.faq') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">FAQs</a>
                            <a href="{{ route('info.dress-code') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">Dress Code</a>
                            <a href="{{ route('info.contact') }}"
                                class="block px-4 py-2 text-sm hover:bg-gray-100">Contact Us</a>
                        </div>
                    </div>
                </nav>

                <!-- Auth / Profile -->
                <div class="hidden md:block">
                    @guest
                        <button @click="$dispatch('open-login-modal')"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Login
                        </button>
                    @else
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-20">
                                @if (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Dashboard</a>
                                @endif
                                <a href="{{ route('profile.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="{{ route('profile.my-bookings') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Bookings</a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Log
                                        Out</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Hamburger -->
                <div class="md:hidden">
                    <button @click="isMobileMenuOpen = true"
                        class="text-gray-700 hover:text-blue-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Slide Menu -->
        <div x-show="isMobileMenuOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-x-full"
            x-transition:enter-end="opacity-100 transform translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-x-0"
            x-transition:leave-end="opacity-0 transform -translate-x-full"
            class="fixed inset-0 z-50 md:hidden">
            <div @click="isMobileMenuOpen = false" class="fixed inset-0 bg-black bg-opacity-50"></div>
            <div class="relative w-64 h-full bg-white p-4">
                <button @click="isMobileMenuOpen = false" class="absolute top-4 right-4 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <nav class="mt-10 flex flex-col space-y-4">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="/about" class="text-blue-600 font-semibold">About</a>

                    <h3 class="font-semibold text-gray-500 text-sm pt-2 border-t">Temples</h3>
                    @foreach ($allTemples as $temple)
                        <a href="{{ route('temples.show', $temple->id) }}"
                            class="pl-4 text-gray-700 hover:text-blue-600">{{ $temple->name }}</a>
                    @endforeach

                    <h3 class="font-semibold text-gray-500 text-sm pt-2 border-t">Online Services</h3>
                    <a href="{{ route('booking.index') }}" class="pl-4 text-gray-700 hover:text-blue-600">Darshan Booking</a>
                    <a href="{{ route('sevas.booking.index') }}" class="pl-4 text-gray-700 hover:text-blue-600">Sevas</a>
                    <a href="#" class="pl-4 text-gray-700 hover:text-blue-600">Accommodation Booking</a>

                    <h3 class="font-semibold text-gray-500 text-sm pt-2 border-t">General Info</h3>
                    <a href="{{ route('info.sevas') }}" class="pl-4 text-gray-700 hover:text-blue-600">Seva Info</a>
                    <a href="{{ route('info.faq') }}" class="pl-4 text-gray-700 hover:text-blue-600">FAQs</a>
                    <a href="{{ route('info.dress-code') }}" class="pl-4 text-gray-700 hover:text-blue-600">Dress Code</a>
                    <a href="{{ route('info.contact') }}" class="pl-4 text-gray-700 hover:text-blue-600">Contact Us</a>

                    <div class="pt-4 border-t">
                        @guest
                            <a href="#" @click="loginModal = true; isMobileMenuOpen = false"
                                class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">Login</a>
                        @else
                            <a href="{{ route('profile.my-bookings') }}"
                                class="block mb-2 text-gray-700 hover:text-blue-600">My Bookings</a>
                            <a href="{{ route('profile.edit') }}"
                                class="block mb-4 text-gray-700 hover:text-blue-600">Profile</a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left text-red-600 hover:bg-gray-100 p-2 rounded-md">Log Out</button>
                            </form>
                        @endguest
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-10 space-y-16">
        <section class="text-center animate-fadeInUp">
            <h2 class="text-3xl font-bold text-blue-800 mb-4 tracking-wide">“सर्वे भवन्तु सुखिनः, सर्वे सन्तु
                निरामयाः।”</h2>
            <p class="text-gray-700 mb-4">(May all be happy, may all be free from illness.)</p>
        </section>
        <section class="text-center animate-fadeInUp">
            <h2 class="text-3xl font-bold text-blue-800 mb-4 tracking-wide">🌟 Our Mission</h2>
            <p class="text-gray-700 mb-4">To connect every pilgrim with the divine — through technology, trust, and
                tradition.</p>
            <a href=""
                class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded shadow transition-all duration-300">Join
                Us</a>
        </section>
        <section class="bg-white p-6 rounded shadow-md animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-700 mb-6 tracking-wide text-center">📦 What We Offer</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-center">
                @foreach ([
        ['📅', 'Darshan Booking', 'Book online darshan slots at top temples.'],
        ['🙏', 'Sevas & Poojas', 'Participate in temple rituals in-person or virtually.'],
        ['🛌', 'Accommodation', 'Find temple rooms or nearby partner hotels.'],
        // ['🚕', 'Cab Booking', 'Pre-book cabs for safe travel.'],
        ['📖', 'E-Books & Resources', 'Read holy texts and temple histories.'],
        ['💰', 'Donations', 'Contribute to temples and receive 80G receipt.'],
    ] as $offer)
                    <div class="bg-white p-6 rounded shadow text-center hover:shadow-md transition">
                        <div class="text-4xl mb-2">{{ $offer[0] }}</div>
                        <h3 class="text-lg font-semibold text-blue-700">{{ $offer[1] }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $offer[2] }}</p>

                    </div>
                @endforeach
            </div>
        </section>
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
                seamless experience to all pilgrims.</p>
        </section>
        <section class="bg-white p-6 rounded shadow animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-800 mb-2">🧘 Community & Culture</h2>
            <p>We promote cultural festivals, bhajans, yoga, and knowledge-sharing — creating an inclusive space for
                spiritual growth.</p>
        </section>
        <section class="animate-fadeInUp">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">💻 Technology Meets Tradition</h2>
            <p>From QR-coded darshan tickets to multilingual interfaces and secure payment gateways — we combine
                innovation with devotion.</p>
        </section>
        @guest
            <section class="text-center py-10 animate-fadeInUp">
                <h2 class="text-3xl font-bold text-blue-700 mb-4">Start Your Divine Journey Today</h2>
                <p class="mb-6 text-gray-700">Register now and explore services across hundreds of Indian temples.</p>
                <a href="/register"
                    class="mt-4 inline-block bg-blue-700 hover:bg-blue-800 text-white font-medium px-6 py-2 rounded shadow transition-all duration-300">Register
                    Now</a>
            </section>
        @endguest

    </main>
    <a href="#top"
        class="fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg hover:bg-blue-700 transition hidden md:block z-50">
        ↑ Back to Top
    </a>

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
</body>

</html>
