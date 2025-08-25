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
                <a href="/" class="text-2xl font-bold text-blue-600">DivyaDarshan</a>
            </div>

            <!-- Middle: Menu -->
            <nav class="flex gap-6 text-sm font-medium text-gray-700">
                <a href="/" class="hover:text-blue-600">Home</a>
                <a href="/about" class="text-blue-600 font-semibold">About</a>

                <!-- Temples Dropdown -->
                <div class="relative group">
                    <button aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded focus:outline-none"
                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;"
                        id="templesDropdownBtn">
                        <span>Temples</span>
                        <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
                        role="menu" aria-labelledby="templesDropdownBtn">
                        @foreach ($allTemples as $temple)
                            <a href="{{ route('temples.show', $temple->id) }}"
                                class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap" role="menuitem">
                                {{ $temple->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Online Services Dropdown -->
                <div class="relative group">
                    <button aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded focus:outline-none"
                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;"
                        id="servicesDropdownBtn">
                        <span>Online Services</span>
                        <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
                        role="menu" aria-labelledby="servicesDropdownBtn">
                        <a href="{{ route('booking.index') }}"
                            class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap" role="menuitem">Darshan
                            Booking</a>
                        <a href="{{ route('sevas.booking.index') }}"
                            class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap" role="menuitem">Sevas</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                            role="menuitem">Accommodation Booking</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                            role="menuitem">Cab Booking</a>
                    </div>
                </div>
                <!-- Dropdown: General Info -->
                <div class="relative group">
                    <button aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-1 text-gray-700 hover:text-blue-600 px-3 py-2 rounded focus:outline-none"
                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;"
                        id="servicesDropdownBtn">
                        <span>General Information</span>
                        <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div class="absolute hidden group-hover:block bg-white border rounded shadow mt-1 min-w-max z-20"
                        role="menu" aria-labelledby="servicesDropdownBtn">
                        <a href="{{ route('info.faq') }}" class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                            role="menuitem">FAQs</a>
                        <a href="{{ route('info.sevas') }}" class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap"
                            role="menuitem">Sevas</a>
                        <a href="{{ route('info.dress-code') }}"
                            class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap" role="menuitem">Dress Code</a>
                        <a href="{{ route('info.contact') }}"
                            class="block px-4 py-2 hover:bg-gray-100 whitespace-nowrap" role="menuitem">Contact Us</a>
                    </div>
                </div>
            </nav>

            <!-- Right: Login Button -->
            <div class="flex-shrink-0">
                @guest
                    {{-- This button shows only if the user is a GUEST --}}
                    <button @click="loginModal = true; modalView = 'login'"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Login
                    </button>
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

    <!-- 🔻 Footer -->
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
<style>
    html {
        scroll-behavior: smooth;
    }
</style>
