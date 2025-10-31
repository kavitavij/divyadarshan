@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="container mx-auto px-4">
            <div class="bg-green-100 border-green-400 text-green-700 dark:bg-green-900/50 dark:border-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-6"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    {{-- Our Services Section --}}
    <section class="py-16 bg-gray-50 dark:bg-gradient-to-b dark:from-slate-900 dark:to-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-4">Our Services</h2>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto">From sacred rituals to
                comfortable stays, we provide all the essential services for your spiritual journey, seamlessly connected in
                one place.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $modules = [
                        [
                            'icon' => 'fa-gopuram',
                            'title' => 'Temple Info',
                            'desc' => 'Explore temple details, timings, and history.',
                            'url' => $firstTemple ? route('temples.show', $firstTemple->id) : route('temples.index'),
                        ],
                        [
                            'icon' => 'fa-calendar-check',
                            'title' => 'Book Darshan',
                            'desc' => 'Secure your spot with real-time slot booking.',
                            'url' => route('booking.index'),
                        ],
                        [
                            'icon' => 'fa-bed',
                            'title' => 'Accommodation',
                            'desc' => 'Find and book comfortable stays near temples.',
                            'url' => route('stays.index'),
                        ],
                        [
                            'icon' => 'fa-praying-hands',
                            'title' => 'Sevas & Poojas',
                            'desc' => 'Participate in sacred rituals from anywhere.',
                            'url' => route('sevas.booking.index'),
                        ],
                        [
                            'icon' => 'fa-hand-holding-heart',
                            'title' => 'Donations',
                            'desc' => 'Contribute to temples and receive blessings.',
                            'url' => route('donations.index'),
                        ],
                        [
                            'icon' => 'fa-book-open',
                            'title' => 'E-Books',
                            'desc' => 'Access a library of spiritual texts.',
                            'url' => route('ebooks.index'),
                        ],
                        [
                            'icon' => 'fa-headset',
                            'title' => 'Spiritual Guidance',
                            'desc' => 'Connect with experts for spiritual advice.',
                            'url' => '#',
                        ],
                        [
                            'icon' => 'fa-star',
                            'title' => 'Devotees Reviews',
                            'desc' => 'Read experiences from fellow devotees.',
                            'url' => route('reviews.index'),
                        ],
                    ];
                @endphp
                @foreach ($modules as $module)
                    @if (isset($module['title']) && $module['title'] === 'Spiritual Guidance')
                        <button type="button" @click="spiritualHelpModal = true"
                            class="group block text-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-yellow-400 dark:hover:border-yellow-500">
                            <div
                                class="mx-auto mb-4 w-16 h-16 rounded-full bg-yellow-100 dark:bg-gray-800 text-yellow-600 dark:text-yellow-400 flex items-center justify-center text-3xl transition-all duration-300 group-hover:bg-yellow-400 group-hover:text-white dark:group-hover:bg-yellow-500 dark:group-hover:text-gray-900 group-hover:rotate-12">
                                <i class="fas {{ $module['icon'] }}"></i>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-gray-100 transition-colors group-hover:text-yellow-600 dark:group-hover:text-yellow-400">
                                {{ $module['title'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">{{ $module['desc'] }}</p>
                        </button>
                    @else
                        <a href="{{ $module['url'] }}"
                            class="group block text-center bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl p-8 transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:border-yellow-400 dark:hover:border-yellow-500">
                            <div
                                class="mx-auto mb-4 w-16 h-16 rounded-full bg-yellow-100 dark:bg-gray-800 text-yellow-600 dark:text-yellow-400 flex items-center justify-center text-3xl transition-all duration-300 group-hover:bg-yellow-400 group-hover:text-white dark:group-hover:bg-yellow-500 dark:group-hover:text-gray-900 group-hover:rotate-12">
                                <i class="fas {{ $module['icon'] }}"></i>
                            </div>
                            <h3
                                class="text-lg font-semibold text-gray-800 dark:text-gray-100 transition-colors group-hover:text-yellow-600 dark:group-hover:text-yellow-400">
                                {{ $module['title'] }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">{{ $module['desc'] }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Book With Us Section --}}
    <section class="py-12 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-4">
                Why Book With DivyaDarshan
            </h2>
            <p class="text-center text-gray-500 dark:text-gray-400 mb-10 max-w-2xl mx-auto">
                Experience a seamless and authentic spiritual journey with our trusted platform, designed for the modern
                devotee.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div
                    class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-indigo-50 dark:hover:bg-gray-700">
                    <div
                        class="mx-auto mb-4 w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center transition-colors duration-300 hover:bg-indigo-200 dark:hover:bg-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">2L+ Devotees Served</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Join a growing community of devotees who trust us
                        for their spiritual needs.</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-indigo-50 dark:hover:bg-gray-700">
                    <div
                        class="mx-auto mb-4 w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center transition-colors duration-300 hover:bg-indigo-200 dark:hover:bg-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">Verified & Trusted</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">We partner exclusively with renowned and authentic
                        temples and hotels.</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-indigo-50 dark:hover:bg-gray-700">
                    <div
                        class="mx-auto mb-4 w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center transition-colors duration-300 hover:bg-indigo-200 dark:hover:bg-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 0 2l-.15.08a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1 0-2l.15-.08a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">Authentic Rituals</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">All services are performed by certified priests
                        following Vedic traditions.</p>
                </div>
                <div
                    class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center transition-transform transform hover:scale-105 hover:shadow-xl hover:bg-indigo-50 dark:hover:bg-gray-700">
                    <div
                        class="mx-auto mb-4 w-14 h-14 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center transition-colors duration-300 hover:bg-indigo-200 dark:hover:bg-indigo-800">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m12 19-7-7 7-7" />
                            <path d="M19 12H5" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100">Seamless Experience</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">A simple, secure, and hassle-free booking process
                        from start to finish.</p>
                </div>
            </div>
        </div>
    </section>
    {{-- Featured Temples Section --}}
    <div class="py-12 bg-gray-50 dark:bg-gray-900">
        <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-8">Featured Temples</h2>

        <div class="swiper temple-swiper">
            <div class="swiper-wrapper">
                @foreach ($temples as $temple)
                    <div class="swiper-slide">
                        <a href="{{ route('temples.show', $temple->id) }}"
                            class="temple-card block rounded-xl overflow-hidden shadow hover:shadow-lg relative">
                            <img src="{{ asset($temple->image) }}" alt="{{ $temple->name }}"
                                class="w-full h-64 object-cover">
                            <!-- Transparent overlay ensures image is fully clickable -->
                            <span class="absolute inset-0 z-10"></span>
                            <div class="p-4 bg-white dark:bg-gray-800 relative z-20">
                                <h3
                                    class="text-lg font-semibold text-gray-800 dark:text-gray-100 group-hover:text-yellow-500">
                                    {{ $temple->name }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $temple->location }}</p>
                                {{-- <span class="inline-block mt-2 text-yellow-500 font-medium">View Details →</span> --}}
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>

        </div>
    </div>
    {{-- How It Works Section --}}
    <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-900 ...">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-10">A Simple Path to Devotion</h2>
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div class="flex justify-center">
                    <div class="w-full md:w-4/5 aspect-video bg-gray-200 rounded-lg shadow-lg overflow-hidden">
                        <video class="w-full h-full" controls>
                            <source
                                src="{{ asset('storage/font-video/DivyaDarshan - Google Chrome 2025-09-16 09-56-35 - Trim.mp4') }}"
                                type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                <div class="relative flex flex-col items-start space-y-8">
                    <div class="flow-step">
                        <div class="flow-icon">1</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Choose Your Service</h3>
                            <p class="text-gray-500 dark:text-gray-400">Select a darshan, seva, or accommodation from our
                                wide range of options.</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-icon">2</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Provide Your Details</h3>
                            <p class="text-gray-500 dark:text-gray-400">Enter the required information for all devotees and
                                complete the booking.</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-icon">3</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Secure Payment</h3>
                            <p class="text-gray-500 dark:text-gray-400">Pay securely through our trusted payment gateway to
                                confirm your service.</p>
                        </div>
                    </div>
                    <div class="flow-step">
                        <div class="flow-icon">4</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Receive Confirmation</h3>
                            <p class="text-gray-500 dark:text-gray-400">Get instant confirmation and receipts on WhatsApp
                                and your email.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>

        {{-- Latest Updates --}}
        <section class="py-16 bg-gray-50 dark:bg-gradient-to-br dark:from-gray-900 dark:to-slate-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-gray-800 dark:text-yellow-400 mb-10">
                    Latest Updates
                </h2>
                <div
                    class="max-w-2xl mx-auto bg-slate-800/50 backdrop-blur-sm p-6 rounded-xl shadow-2xl border border-slate-700 updates-panel min-h-[340px]">
                    <div class="updates-scroll space-y-4">
                        @if (isset($latestUpdates) && $latestUpdates->isNotEmpty())
                            @foreach ($latestUpdates as $update)
                                <div
                                    class="notification-item flex items-center gap-x-4 text-gray-200 p-3 rounded-lg bg-slate-700/50 border border-slate-600">
                                    <span class="text-yellow-400 text-xl"></span>
                                    <span class="flex-1">{{ $update->message }}</span>
                                </div>
                            @endforeach
                            @foreach ($latestUpdates as $update)
                                <div
                                    class="notification-item flex items-center gap-x-4 text-gray-200 p-3 rounded-lg bg-slate-700/50 border border-slate-600">
                                    <span class="text-yellow-400 text-xl"></span>
                                    <span class="flex-1">{{ $update->message }}</span>
                                </div>
                            @endforeach
                        @else
                            <div
                                class="notification-item flex items-center gap-x-4 text-gray-200 p-3 rounded-lg bg-slate-700/50 border border-slate-600">
                                <span class="text-yellow-400 text-xl">ℹ️</span>
                                <span class="flex-1">No updates at the moment. Check back soon!</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Spiritual Guidance Section --}}
        <section class="spiritual-guidance-section">
            <div class="guidance-overlay"></div>
            <div class="guidance-content">
                <h2 class="text-3xl font-bold text-yellow-400 mb-4">Confused About Which Puja to Choose?</h2>
                <p class="text-white mb-8 max-w-xl mx-auto">
                    Whether it’s for health, prosperity, or spiritual growth, our expert priests are here to guide you in
                    selecting the perfect ritual for your divine journey.
                </p>

                <button @click="spiritualHelpModal = true" class="guidance-button">
                    Get Divine Guidance
                </button>
            </div>
        </section>
    @endsection

    @push('styles')
        <style>
            /* Remove dark overlay for temple slider only */
            .temple-swiper .swiper-slide::after {
                background: none !important;
            }

            .scrollbar-thin::-webkit-scrollbar {
                height: 8px;
            }

            .scrollbar-thin::-webkit-scrollbar-track {
                background: #e5e7eb;
            }

            .scrollbar-thin::-webkit-scrollbar-thumb {
                background: #facc15;
                border-radius: 4px;
            }

            .temple-swiper .swiper-slide {
                width: 300px;
                pointer-events: auto !important;
            }

            .temple-card img {
                display: block;
            }

            .temple-card span.absolute {
                pointer-events: auto;
            }

            .temple-swiper .swiper-pagination-bullet-active {
                background: #facc15;
            }

            /* Swiper navigation */
            .temple-nav-next,
            .temple-nav-prev {
                color: #facc15;
                font-size: 2rem;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 20;
                cursor: pointer;
            }

            .temple-nav-next {
                right: 10px;
            }

            .temple-nav-prev {
                left: 10px;
            }

            /* === FIXES FOR CLICK ISSUES === */
            .temple-swiper .swiper-slide {
                pointer-events: none;
                /* disable clicks for all slides */
            }

            .temple-swiper .swiper-slide-active,
            .temple-swiper .swiper-slide-next,
            .temple-swiper .swiper-slide-prev {
                pointer-events: auto;
                /* enable only visible slides */
            }

            .swiper-slide::after {
                content: '';
                position: absolute;
                left: 0;
                bottom: 0;
                width: 100%;
                height: 60%;
                background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
                pointer-events: none;
            }

            .view-details-button {
                position: relative;
                z-index: 50;
                /* higher than cloned slides */
                pointer-events: auto;
            }

            .temple-swiper .swiper-pagination-bullet-active {
                background-color: #facc15;
            }

            /* Navigation Buttons Position */
            .temple-nav-next,
            .temple-nav-prev {
                color: #fff;
                background-color: rgba(0, 0, 0, 0.3);
                border-radius: 50%;
                width: 44px;
                height: 44px;
                transition: background-color 0.3s ease;
            }

            .temple-nav-next:hover,
            .temple-nav-prev:hover {
                background-color: rgba(0, 0, 0, 0.5);
            }

            .temple-nav-next::after,
            .temple-nav-prev::after {
                font-size: 20px;
                font-weight: bold;
            }

            .flow-step {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }

            .flow-icon {
                width: 48px;
                height: 48px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background-color: #facc15;
                color: #1f2937;
                font-weight: bold;
                font-size: 1.25rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .updates-panel {
                height: 200px;
                overflow: hidden;
                position: relative;
            }

            .updates-scroll {
                display: flex;
                flex-direction: column;
                animation: scroll-up 20s linear infinite;
                will-change: transform;
                transition: animation-duration 0.3s ease;
            }

            .updates-panel:hover .updates-scroll {
                animation-play-state: paused;
            }

            }

            */ .notification-item {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 1rem;
                font-weight: 500;
                color: #374151;
                font-size: 1rem;
                text-align: left;
            }

            .dark .notification-item {
                color: #d1d5db;
            }

            @keyframes scroll-up {
                0% {
                    transform: translateY(0);
                }

                100% {
                    transform: translateY(-50%);
                }
            }

            /* Spiritual Guidance Section */
            .spiritual-guidance-section {
                position: relative;
                background: url('https://img1.hotstarext.com/image/upload/f_auto/sources/r1/cms/prod/9460/1379460-i-2b70cca05890') center/cover no-repeat;
                padding: 80px 15px;
                text-align: center;
                font-family: 'Poppins', sans-serif;
            }

            .guidance-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.65);
            }

            .guidance-content {
                position: relative;
                z-index: 2;
                max-width: 650px;
                margin: 0 auto;
            }

            .guidance-button {
                background: linear-gradient(135deg, #facc15, #f59e0b);
                color: #000;
                padding: 14px 32px;
                font-size: 1rem;
                font-weight: 600;
                border-radius: 30px;
                text-decoration: none;
                box-shadow: 0 4px 15px rgba(250, 204, 21, 0.35);
                transition: all 0.3s ease-in-out;
                display: inline-block;
            }

            .guidance-button:hover {
                transform: scale(1.05);
                box-shadow: 0 6px 20px rgba(250, 204, 21, 0.45);
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const templeSwiper = new Swiper('.temple-swiper', {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false
                    },
                    coverflowEffect: {
                        rotate: 20,
                        stretch: 0,
                        depth: 150,
                        modifier: 1.5,
                        slideShadows: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    navigation: {
                        nextEl: '.temple-nav-next',
                        prevEl: '.temple-nav-prev'
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30
                        },
                    }
                });

            });
        </script>
    @endpush
