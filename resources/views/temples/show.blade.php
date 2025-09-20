@extends('layouts.app')

@section('content')
 <div class="container mx-auto py-5" x-data="{
        isModalOpen: false,
        modalTitle: '',
        serviceType: ''}">
     @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md mb-6" role="alert">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    <div class="container mx-auto py-5">
        <h1 class="text-3xl font-bold mb-4 text-center text-blue-700 dark:text-blue-400">
            {{ $temple->name }}
        </h1>

        <div class="mb-6 flex justify-center">
            <img src="{{ asset($temple->image) }}" alt="{{ $temple->name }}"
                class="w-40 h-40 object-cover rounded-full shadow-lg mb-6 mx-auto">
        </div>

        {{-- Tab Navigation --}}
        <nav class="mb-6 border-b border-gray-300 dark:border-gray-700">
            <ul class="flex flex-wrap justify-center gap-x-6 sm:gap-x-8 text-sm sm:text-base">
                <li><a href="#about" class="tab-link">About</a></li>
                <li><a href="#services"
                        class="tab-link text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 pb-2 border-b-2 border-transparent">Our
                        Services</a></li>
                <li><a href="#slots"
                        class="tab-link text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 pb-2 border-b-2 border-transparent">Slot
                        Booking</a></li>
                         <li><a href="#gallery" class="tab-link text-gray-600 ...">Gallery</a></li>
                <li><a href="#news"
                        class="tab-link text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 pb-2 border-b-2 border-transparent">News</a></li>
                <li><a href="#social"
                        class="tab-link text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 pb-2 border-b-2 border-transparent">Social
                        Services</a></li>
            </ul>
        </nav>

        {{-- About Tab Content --}}
       <div id="about" class="tab-content">
        <h2 class="text-2xl font-semibold mb-4 text-center dark:text-white">
            About {{ $temple->name }}
        </h2>
        <div class="prose max-w-none text-gray-800 dark:prose-invert dark:text-gray-200 leading-relaxed">
            {!! $temple->about ?: '<p>About info not available yet.</p>' !!}
        </div>
    </div>
        {{-- Our Services Tab Content --}}
        <div id="services" class="tab-content hidden">
            <h2 class="text-2xl font-semibold mb-4 text-center dark:text-white">Our Services at {{ $temple->name }}</h2>
            <p class="text-center text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-6">
                At {{ $temple->name }}, we are committed to serving devotees through a variety of offerings.
                From spiritual guidance and seva opportunities to accommodation and charitable services,
                our mission is to create a meaningful and fulfilling experience for every visitor.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
                {{-- Darshan Booking --}}
                @if (in_array('darshan', $temple->offered_services ?? []))
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-eye text-4xl text-yellow-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Darshan Booking</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Book your Darshan slots online and experience the
                            divine blessings of {{ $temple->name }}.</p>
                        <a href="{{ route('booking.index') }}" class="btn-service bg-blue-600 text-white">Book Now</a>
                    </div>
                @endif

                {{-- Seva Offerings --}}
                @if (in_array('seva', $temple->offered_services ?? []))
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-hand-holding-heart text-4xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Seva Offerings</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Offer seva and participate in rituals to seek
                            blessings from the deity.</p>
                        <a href="{{ route('sevas.booking.index') }}" class="btn-service bg-green-600 text-white">Offer Seva</a>
                    </div>
                @endif

                {{-- Accommodation --}}
                @if (in_array('accommodation', $temple->offered_services ?? []))
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-bed text-4xl text-purple-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Accommodation</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Find comfortable and affordable stays near the holy
                            {{ $temple->name }} temple.</p>
                        <a href="{{ route('stays.index', ['temple_id' => $temple->id]) }}"
                            class="btn-service bg-purple-600 text-white">Book Stay</a>
                    </div>
                @endif

                {{-- Donation --}}
                @if (in_array('donation', $temple->offered_services ?? []))
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-hand-holding-usd text-4xl text-pink-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Donation</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Support temple activities and contribute towards
                            seva, and other services of {{ $temple->name }} temple.</p>
                        <a href="{{ route('donations.index') }}" class="btn-service bg-pink-600 text-white">Donate</a>
                    </div>
                @endif

                {{-- E-Books --}}
                @if (in_array('ebooks', $temple->offered_services ?? []))
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-book text-4xl text-indigo-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Spiritual E-Books</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Read and download scriptures, bhajans, and stories
                            of {{ $temple->name }} temple.</p>
                        <a href="{{ route('ebooks.index') }}" class="btn-service bg-indigo-600 text-white">Browse E-Books</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Slot Booking Tab Content --}}
        <div id="slots" class="tab-content hidden">
            <div class="form-group mt-4">
                <h2 class="text-2xl font-semibold mb-4 text-center dark:text-white">Darshan Slot Booking</h2>
                <p class="text-center text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-6">
                    Devotees can conveniently check and book available darshan slots online.
                    Please select your preferred date and time to ensure a smooth and divine darshan experience at
                    {{ $temple->name }}.
                </p>

                <div class="text-center mt-4">
                    <a href="{{ route('booking.index') }}" class="btn btn-lg px-5 py-2"
                        style="background: linear-gradient(135deg, #b92c28, #e74c3c);
                       color: #fff;
                       border-radius: 30px;
                       font-weight: 600;
                       box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
                       transition: 0.3s;">
                        Book Slot
                    </a>
                </div>
            </div>
        </div>
        <div id="gallery" class="tab-content hidden">

            @php
                $galleryImagePaths = $temple->galleryImages->pluck('path')->map(function ($path) {
                    return asset('storage/' . $path);
                })->all();
            @endphp

            <div x-data="{
                lightboxOpen: false,
                lightboxImage: '',
                galleryImages: {{ json_encode($galleryImagePaths) }},
                currentIndex: 0,
                openLightbox(index) {
                    this.currentIndex = index;
                    this.lightboxImage = this.galleryImages[this.currentIndex];
                    this.lightboxOpen = true;
                },
                nextImage() {
                    if (this.galleryImages.length === 0) return;
                    this.currentIndex = (this.currentIndex + 1) % this.galleryImages.length;
                    this.lightboxImage = this.galleryImages[this.currentIndex];
                },
                prevImage() {
                    if (this.galleryImages.length === 0) return;
                    this.currentIndex = (this.currentIndex - 1 + this.galleryImages.length) % this.galleryImages.length;
                    this.lightboxImage = this.galleryImages[this.currentIndex];
                }
            }">
                <h2 class="text-2xl font-semibold mb-6 text-center dark:text-white">
                    Photo Gallery of {{ $temple->name }}
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($temple->galleryImages as $index => $image)
                        <div @click="openLightbox({{ $index }})">
                            <img class="h-auto max-w-full rounded-lg shadow-md hover:shadow-xl transition-shadow cursor-pointer"
                                src="{{ asset('storage/' . $image->path) }}"
                                alt="Image of {{ $temple->name }}">
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500 dark:text-gray-400">
                            The photo gallery for this temple is not yet available.
                        </p>
                    @endforelse
                </div>

                <div x-show="lightboxOpen" x-cloak
                    @keydown.window.arrow-right.prevent="if (lightboxOpen) nextImage()"
                    @keydown.window.arrow-left.prevent="if (lightboxOpen) prevImage()"
                    x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4">

                    <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white text-3xl z-50 focus:outline-none">&times;</button>

                    <div @click.away="lightboxOpen = false" class="relative w-full max-w-4xl max-h-full flex items-center justify-center">

                        <button @click.stop="prevImage()" x-show="galleryImages.length > 1"
                                class="absolute left-0 md:-left-12 p-2 text-white bg-black bg-opacity-50 rounded-full hover:bg-opacity-75 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>

                        <img :src="lightboxImage" alt="Full-size gallery image" class="rounded-lg shadow-lg object-contain max-h-[85vh]">

                        <button @click.stop="nextImage()" x-show="galleryImages.length > 1"
                                class="absolute right-0 md:-right-12 p-2 text-white bg-black bg-opacity-50 rounded-full hover:bg-opacity-75 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    {{-- News Contant --}}
        <div id="news" class="tab-content hidden py-16 bg-gradient-to-br from-yellow-50 via-white to-yellow-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="container mx-auto px-4" x-data="{ activeNews: 0 }">
        <!-- Heading -->
        <div class="text-center mb-12">
            <h2 class="text-2xl font-semibold mb-4 text-center dark:text-white"">
                News & Updates
            </h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mt-3">
                The latest happenings, announcements, and events at {{ $temple->name }}.
            </p>
        </div>

        @if (!empty($temple->news) && is_array($temple->news))
            <!-- Featured News (First Item) -->
            @php $featured = $temple->news[0]; @endphp
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 mb-10 border border-gray-200 dark:border-slate-700 transition hover:shadow-2xl hover:-translate-y-1">
                <div class="flex flex-col lg:flex-row items-center lg:items-start gap-6">
                    <div class="flex-shrink-0 text-6xl">🌟</div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">Featured Update</h3>
                        <p class="text-gray-700 dark:text-gray-300 text-lg mb-2">
                            {{ $featured['text'] ?? 'Important news update coming soon.' }}
                        </p>
                        <span class="text-sm text-gray-500 dark:text-gray-400 italic">
                            {{ $featured['date'] ?? 'Recently updated' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grid of Other News -->
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach (array_slice($temple->news, 1) as $newsItem)
                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 shadow-md hover:shadow-xl hover:-translate-y-1 transition-transform duration-300">
                        <div class="p-6 flex flex-col h-full">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-yellow-500 text-2xl">📢</span>
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white">Announcement</h4>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 flex-1">
                                {{ $newsItem['text'] ?? 'Update details unavailable.' }}
                            </p>
                            <div class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic">
                                {{ $newsItem['date'] ?? '' }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- No News Message -->
            <div class="text-center bg-white dark:bg-slate-800 p-10 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 max-w-xl mx-auto">
                <span class="block text-6xl mb-4">ℹ️</span>
                <p class="text-gray-800 dark:text-gray-300 text-lg font-medium">
                    Currently, no news has been updated by the temple committee.
                </p>
                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Please check back soon for the latest announcements.
                </p>
            </div>
        @endif
    </div>
</div>

        {{-- Social Services Tab Content --}}
         <div id="social" class="tab-content hidden">
            <h2 class="text-2xl font-semibold mb-4 text-center dark:text-white">Social Services at {{ $temple->name }}</h2>
             <p class="text-center text-gray-600 dark:text-gray-300 max-w-2xl mx-auto mb-6">
                We invite devotees to get involved in our community initiatives. If you wish to volunteer or learn more, please submit your details.
            </p>
             <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

                @if (in_array('annadaan', $temple->offered_social_services ?? []))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-utensils text-4xl text-orange-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Annadaan (Food Donation)</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Help in offering free meals to devotees and the underprivileged.</p>
                        @auth
                            <button @click="isModalOpen = true; modalTitle = 'Get Involved with Annadaan'; serviceType = 'annadaan';"
                                class="btn-service bg-green-600 text-white">
                                Get Involved
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-service bg-green-600 text-white">
                                Get Involved
                            </a>
                        @endauth
                    </div>
                @endif

                @if (in_array('health_camps', $temple->offered_social_services ?? []))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-heartbeat text-4xl text-red-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Health Camps</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Organizing medical check-ups and healthcare services for those in need.</p>
                        @auth
                            <button @click="isModalOpen = true; modalTitle = 'Learn More About Health Camps'; serviceType = 'health_camps';"
                                class="btn-service bg-blue-600 text-white">Learn More</button>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-service bg-blue-600 text-white">
                                Learn More
                            </a>
                        @endauth
                    </div>
                @endif

                @if (in_array('education_aid', $temple->offered_social_services ?? []))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-book-open text-4xl text-yellow-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Education Aid</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Helping underprivileged children with study materials and guidance.</p>
                                 @auth
                            <button @click="isModalOpen = true; modalTitle = 'Inquire About Education Aid'; serviceType = 'education_aid';"
                                class="btn-service bg-yellow-500 text-white">Get Involved</button>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-service  bg-yellow-500 text-white">
                                Get Involved
                            </a>
                        @endauth
                    </div>
                @endif

                @if (in_array('environment_care', $temple->offered_social_services ?? []))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-leaf text-4xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Environment Care</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Tree plantation, cleanliness drives, and campaigns for a greener environment.</p>
                        @auth
                            <button @click="isModalOpen = true; modalTitle = 'Volunteer for Environment Care'; serviceType = 'environment_care';"
                                class="btn-service bg-teal-600 text-white">Join Drive</button>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-service bg-teal-600 text-white    ">
                                Join Drive
                            </a>
                        @endauth
                    </div>
                @endif

                @if (in_array('community_seva', $temple->offered_social_services ?? []))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-users text-4xl text-indigo-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2 dark:text-white">Community Seva</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">Serving the community by helping in disasters, welfare, and volunteering.</p>
                        @auth
                            <button @click="isModalOpen = true; modalTitle = 'Volunteer for Community Seva'; serviceType = 'community_seva';"
                                class="btn-service bg-red-600 text-white">Volunteer</button>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn-service  bg-red-600 text-white">
                                Volunteer
                            </a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        {{--  Social Service Inquiry Modal/Popup Form --}}
        <div x-show="isModalOpen"
            x-cloak
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @keydown.escape.window="isModalOpen = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4">

            <div @click.away="isModalOpen = false"
                class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md mx-auto p-6
                    max-h-[80vh] overflow-y-auto">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white" x-text="modalTitle"></h3>
                    <button @click="isModalOpen = false"
                        class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 text-2xl font-bold">&times;</button>
                </div>

                {{-- Body --}}
                <form action="{{ route('social.service.inquiry.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="service_type" x-model="serviceType">
                    <input type="hidden" name="temple_id" value="{{ $temple->id }}">

                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                            <input type="text" id="name" name="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                    focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700
                                    dark:border-gray-600 dark:text-white px-3 py-1.5">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                    focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700
                                    dark:border-gray-600 dark:text-white px-3 py-1.5">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                            <input type="tel" id="phone" name="phone" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                    focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700
                                    dark:border-gray-600 dark:text-white px-3 py-1.5">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message (Optional)</label>
                            <textarea id="message" name="message" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                    focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700
                                    dark:border-gray-600 dark:text-white px-3 py-1.5"
                                placeholder="e.g., How can I volunteer for this service?"></textarea>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700
                                text-white text-sm font-medium rounded-md shadow
                                transition-transform transform hover:-translate-y-0.5">
                            Submit Inquiry
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <style>
        .banner-container {
            background-size: cover;
            background-position: center;
        }

        .btn-service {
            padding: 0.5rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.2s ease-in-out;
        }
        .btn-service:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }.tab-link.active {
            color: #2563eb; /* blue-600 */
            border-color: #2563eb; /* blue-600 */
            font-weight: 600;
        }
        .dark .tab-link.active {
            color: #60a5fa; /* blue-400 */
            border-color: #60a5fa; /* blue-400 */
        }
</style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function switchTab(targetId) {
                tabLinks.forEach(link => {
                    link.classList.remove('border-blue-600', 'text-blue-600');
                    link.classList.add('border-transparent', 'text-gray-600', 'dark:text-gray-300');
                });

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeLink = document.querySelector(`.tab-link[href="${targetId}"]`);
                const activeContent = document.querySelector(targetId);

                if (activeLink && activeContent) {
                    activeLink.classList.add('border-blue-600', 'text-blue-600', 'dark:text-blue-400');
                    activeLink.classList.remove('text-gray-600', 'dark:text-gray-300');
                    activeContent.classList.remove('hidden');
                }
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId.startsWith('#')) {
                        history.replaceState(null, null, targetId);
                        switchTab(targetId);
                    } else {
                        window.location.href = targetId;
                    }
                });
            });

            const bookNowButtons = document.querySelectorAll('a.btn-service[href="#slots"]');
            bookNowButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    switchTab('#slots');
                });
            });

            const currentHash = window.location.hash;
            if (currentHash && document.querySelector(currentHash)) {
                switchTab(currentHash);
            } else {
                switchTab('#about');
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chargePerPerson = {{ $temple->darshan_charge ?? 0 }};
            const numberInput = document.getElementById('number_of_people');
            const totalChargeEl = document.getElementById('totalCharge');

            if (numberInput) {
                numberInput.addEventListener('input', function() {
                    const people = parseInt(this.value) || 0;
                    const total = people * chargePerPerson;
                    totalChargeEl.textContent = people > 0 ?
                        `Total Charge: ₹${total.toFixed(2)}` :
                        '';
                });
            }
        });
    </script>
@endsection
