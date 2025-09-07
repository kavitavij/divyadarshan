@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-5">
        <h1 class="text-3xl font-bold mb-4 text-center text-blue-700">{{ $temple->name }}</h1>

        <div class="mb-6 flex justify-center">
            <img src="{{ asset($temple->image) }}" alt="{{ $temple->name }}"
                class="w-40 h-40 object-cover rounded-full shadow-lg mb-6 mx-auto">
        </div>

        {{-- Tab Navigation --}}
        <nav class="mb-6 border-b border-gray-300">
            <ul class="flex space-x-8 justify-center flex-wrap">
                <li><a href="#about" class="tab-link">About</a></li>
                <li><a href="#services"
                        class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Our
                        Services</a></li>
                <li><a href="#slots"
                        class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Slot
                        Booking</a></li>
                <li><a href="#news"
                        class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">News</a></li>
                <li><a href="#social"
                        class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Social
                        Services</a></li>
            </ul>
        </nav>

        {{-- About Tab Content --}}
        <div id="about" class="tab-content">
            <h2 class="text-2xl font-semibold mb-4 text-center">About {{ $temple->name }}</h2>
            <div class="prose max-w-none text-center">
                {!! $temple->about ?: '<p>About info not available yet.</p>' !!}
            </div>
        </div>


        {{-- Our Services Tab Content --}}
        <div id="services" class="tab-content hidden">
            <h2 class="text-2xl font-semibold mb-4 text-center">Our Services at {{ $temple->name }}</h2>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-6">
                At {{ $temple->name }}, we are committed to serving devotees through a variety of offerings.
                From spiritual guidance and seva opportunities to accommodation and charitable services,
                our mission is to create a meaningful and fulfilling experience for every visitor.
            </p>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">

                {{-- Darshan Booking --}}
                @if (in_array('darshan', $temple->offered_services ?? []))
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-eye text-4xl text-yellow-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Darshan Booking</h3>
                        <p class="text-gray-600 mb-4">Book your Darshan slots online and experience the divine blessings of
                            {{ $temple->name }}.</p>
                        <a href="#slots" class="btn-service bg-blue-600 text-white">Book Now</a>
                    </div>
                @endif

                {{-- Seva Offerings --}}
                @if (in_array('seva', $temple->offered_services ?? []))
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-hand-holding-heart text-4xl text-green-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Seva Offerings</h3>
                        <p class="text-gray-600 mb-4">Offer seva and participate in rituals to seek blessings from the
                            deity.</p>
                        <a href="#" class="btn-service bg-green-600 text-white">Offer Seva</a>
                    </div>
                @endif

                {{-- Accommodation --}}
                @if (in_array('accommodation', $temple->offered_services ?? []))
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-bed text-4xl text-purple-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Accommodation</h3>
                        <p class="text-gray-600 mb-4">Find comfortable and affordable stays near the holy
                            {{ $temple->name }} temple.</p>
                        <a href="{{ route('stays.index', ['temple_id' => $temple->id]) }}"
                            class="btn-service bg-purple-600 text-white">Book Stay</a>
                    </div>
                @endif
                {{-- Donation --}}
                @if (in_array('donation', $temple->offered_services ?? []))
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-hand-holding-usd text-4xl text-pink-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Donation</h3>
                        <p class="text-gray-600 mb-4">Support temple activities and contribute towards seva, and other
                            services of {{ $temple->name }} temple.</p>
                        <a href="#" class="btn-service bg-pink-600 text-white">Donate</a>
                    </div>
                @endif
                {{-- E-Books --}}
                @if (in_array('ebooks', $temple->offered_services ?? []))
                    <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                        <i class="fas fa-book text-4xl text-indigo-500 mb-4"></i>
                        <h3 class="text-xl font-bold mb-2">Spiritual E-Books</h3>
                        <p class="text-gray-600 mb-4">Read and download scriptures, bhajans, and stories of
                            {{ $temple->name }} temple.</p>
                        <a href="#" class="btn-service bg-indigo-600 text-white">Browse E-Books</a>
                    </div>
                @endif
            </div>
        </div>
        {{-- Slot Booking Tab Content --}}
        <div id="slots" class="tab-content hidden">
            <div class="form-group mt-4">
                <h2 class="text-2xl font-semibold mb-4 text-center">Darshan Slot Booking</h2>
                <p class="text-center text-gray-600 max-w-2xl mx-auto mb-6">
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
        {{-- News Tab Content --}}
        <div id="news" class="tab-content hidden">
            <h2 class="text-2xl font-semibold mb-4 text-center">News & Updates</h2>
            <p class="text-center text-gray-600 max-w-2xl mx-auto mb-6">
                Stay updated with the latest news, announcements, and events happening at {{ $temple->name }}.
                We regularly share important updates for the benefit of all devotees.
            </p>

            @if (!empty($temple->news) && is_array($temple->news))
                <ul class="list-disc pl-0 text-center" style="list-style-position: inside;">
                    @foreach ($temple->news as $newsItem)
                        <li class="mb-2">{{ $newsItem['text'] ?? '' }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-center">Currenty, No news updated by temple commitee .</p>
            @endif
        </div>
        {{-- Social Services Tab Content --}}
        <div id="social" class="tab-content hidden">
            <h2 class="text-2xl font-semibold mb-4 text-center">Social Services at {{ $temple->name }}</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-6">
                <!-- Annadaan Card -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-utensils text-4xl text-orange-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Annadaan (Food Donation)</h3>
                    <p class="text-gray-600 mb-4">Offering free meals to devotees and the underprivileged, spreading the
                        blessings of {{ $temple->name }}.</p>
                    <a href="#" class="btn-service bg-green-600 text-white">Contribute</a>
                </div>
                <!-- Health Camps Card -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-heartbeat text-4xl text-red-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Health Camps</h3>
                    <p class="text-gray-600 mb-4">Organizing medical check-ups, blood donation, and healthcare services for
                        those in need.</p>
                    <a href="#" class="btn-service bg-blue-600 text-white">Support</a>
                </div>

                <!-- Education Aid Card -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-book-open text-4xl text-yellow-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Education Aid</h3>
                    <p class="text-gray-600 mb-4">Helping underprivileged children with study materials, scholarships, and
                        guidance programs.</p>
                    <a href="#" class="btn-service bg-yellow-500 text-white">Help Educate</a>
                </div>

                <!-- Environment Care Card -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-leaf text-4xl text-green-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Environment Care</h3>
                    <p class="text-gray-600 mb-4">Tree plantation, cleanliness drives, and campaigns for a greener and
                        healthier environment.</p>
                    <a href="#" class="btn-service bg-teal-600 text-white">Join Drive</a>
                </div>

                <!-- Community Seva Card -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition-shadow">
                    <i class="fas fa-users text-4xl text-indigo-500 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Community Seva</h3>
                    <p class="text-gray-600 mb-4">Serving the community by helping in natural disasters, social welfare,
                        and temple volunteering.</p>
                    <a href="#" class="btn-service bg-red-600 text-white">Volunteer</a>
                </div>
            </div>
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
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function switchTab(targetId) {
                tabLinks.forEach(link => {
                    link.classList.remove('border-blue-600', 'text-blue-600');
                    link.classList.add('border-transparent', 'text-gray-600');
                });

                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeLink = document.querySelector(`.tab-link[href="${targetId}"]`);
                const activeContent = document.querySelector(targetId);

                if (activeLink && activeContent) {
                    activeLink.classList.add('border-blue-600', 'text-blue-600');
                    activeLink.classList.remove('text-gray-600');
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
