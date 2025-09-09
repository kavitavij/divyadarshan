@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="container mx-auto px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Our Services -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Our Services</h2>
        <p class="text-center text-gray-600 mb-8">All essential services for your spiritual journey</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-4">
            @php
                $modules = [
                    [
                        'icon' => '🛕',
                        'title' => 'Temple Info',
                        'desc' => 'Browse temple details, timings, maps.',
                        'url' => $firstTemple ? route('temples.show', $firstTemple->id) : route('temples.index'),
                    ],
                    [
                        'icon' => '📅',
                        'title' => 'Book Darshan',
                        'desc' => 'Choose slots for special/general darshan.',
                        'url' => route('booking.index'),
                    ],
                    [
                        'icon' => '🛌',
                        'title' => 'Accommodation',
                        'desc' => 'Book temple or partner hotel rooms.',
                        'url' => route('stays.index'),
                    ],
                    [
                        'icon' => '🙏',
                        'title' => 'Sevas & Poojas',
                        'desc' => 'Participate in poojas online or in-person.',
                        'url' => route('sevas.booking.index'),
                    ],
                    [
                        'icon' => '💰',
                        'title' => 'Donations',
                        'desc' => 'Make donations with instant receipts.',
                        'url' => route('donations.index'),
                    ],
                    [
                        'icon' => '📖',
                        'title' => 'E-Books',
                        'desc' => 'View and download spiritual texts.',
                        'url' => route('ebooks.index'),
                    ],
                    [
                        'icon' => '🌐',
                        'title' => 'Languages',
                        'desc' => 'Books available in multiple languages.',
                        'url' => route('ebooks.index'),
                    ],
                ];
            @endphp
            @foreach ($modules as $module)
                <div class="why-card">
                    <button type="button" class="text-4xl mb-2 hover:text-blue-700 focus:outline-none"
                        onclick="window.location.href='{{ $module['url'] }}'" aria-label="{{ $module['title'] }} button">
                        {{ $module['icon'] }}
                    </button>

                    <h3 class="text-lg font-semibold text-blue-700">{{ $module['title'] }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $module['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

<!-- ✅ Why Book With Us -->
<div class="mb-12 px-4">
    <h2 class="text-2xl font-bold text-center text-blue-800 mb-3">Why Book With Us</h2>
    <p class="text-center text-gray-600 mb-8">Here to guide you through your challenges</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <div class="why-card">
            <div class="text-4xl mb-3 text-red-600">🕉️</div>
            <h3 class="font-semibold text-lg text-blue-900">2L+ Devotees Served</h3>
            <p class="text-gray-800 text-sm mt-2">Rituals by certified priests with authentic Vedic practice.</p>
        </div>
        <div class="why-card">
            <div class="text-4xl mb-3 text-red-600">🏯</div>
            <h3 class="font-semibold text-lg text-blue-900">Trusted Temples</h3>
            <p class="text-gray-800 text-sm mt-2">Book services at renowned and trusted temples across the region.</p>
        </div>
        <div class="why-card">
            <div class="text-4xl mb-3 text-red-600">🍲</div>
            <h3 class="font-semibold text-lg text-blue-900">Prasad Delivery</h3>
            <p class="text-gray-800 text-sm mt-2">Receive blessed prasad delivered to your doorstep after every booking.</p>
        </div>
        <div class="why-card">
            <div class="text-4xl mb-3 text-red-600">📖</div>
            <h3 class="font-semibold text-lg text-blue-900">Easy Booking</h3>
            <p class="text-gray-800 text-sm mt-2">Simple and hassle-free booking process for all your spiritual needs.</p>
        </div>
    </div>
</div>

    <!-- 🛕 Temple Section -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-blue-800 mb-6 text-center">Temples</h2>

        <!-- 🔍 Search Bar -->
        <form method="GET" action="{{ route('home') }}" class="mb-6 flex justify-center">
            <input type="text" name="search" placeholder="Search temples..." value="{{ request('search') }}"
                class="border p-2 rounded w-full md:w-1/3 shadow">
        </form>
        <!-- 📜 Temple Cards -->
        @if ($temples->total() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-4">
                @foreach ($temples as $temple)
                    <div
                        class="bg-white p-4 rounded shadow hover:shadow-lg transition text-center max-w-xs w-full mx-auto flex flex-col h-full animate-fadeIn">
                        <img src="{{ asset($temple->image) }}" alt="{{ $temple->name }}"
                            class="w-full h-48 object-cover rounded mb-3">

                        <h3 class="text-lg font-bold text-blue-700">{{ $temple->name }}</h3>
                        <p class="text-sm text-gray-600 mb-3">{{ $temple->location }}</p>

                        <div class="mt-auto">
                            <a href="{{ route('temples.show', $temple->id) }}" class="view-details-button">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $temples->links() }}
            </div>
        @else
            <p class="text-center text-gray-600">No temples found.</p>
        @endif
    </div>
    <!-- <div class="mb-12 px-4">
                <!-- Heading -->
    <!-- <h2 class="text-xl font-semibold mb-4 text-center text-blue-800">Latest Updates</h2> -->
    <!-- <div class="vertical-panel">
                    <div class="vertical-scroll">
                        <div class="notification-item">🔔 Special Darshan opened for Navratri week.</div>
                        <div class="notification-item">🏨 New hotel partners added for Tirupati region.</div>
                        <div class="notification-item">📖 New e-book on "Temple Rituals of South India" uploaded.</div>
                        <div class="notification-item">🌐 Language support extended to Bengali and Marathi.</div>
                    </div>
                </div>
            </div> -->
            <!-- ✅ How It Works (Flowchart Section) -->
<section class="mb-12 px-4">
    <h2 class="text-2xl font-bold text-center text-blue-800 mb-6">How It Works</h2>
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

        <!-- Flowchart Left -->
        <div class="relative flex flex-col items-start space-y-10">
            <!-- Step 1 -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 text-black font-bold shadow-lg">
                    1
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Select Temple</h3>
                    <p class="text-gray-600 text-sm">Choose your desired temple from our list.</p>
                </div>
            </div>

            <!-- Connector -->
            <div class="border-l-2 border-dashed border-yellow-400 h-10 ml-6"></div>

            <!-- Step 2 -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 text-black font-bold shadow-lg">
                    2
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Select Offering/Service</h3>
                    <p class="text-gray-600 text-sm">Pick puja, seva, or special ritual.</p>
                </div>
            </div>

            <div class="border-l-2 border-dashed border-yellow-400 h-10 ml-6"></div>

            <!-- Step 3 -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 text-black font-bold shadow-lg">
                    3
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Fill Your Details</h3>
                    <p class="text-gray-600 text-sm">Provide your name, gotra, and other details.</p>
                </div>
            </div>

            <div class="border-l-2 border-dashed border-yellow-400 h-10 ml-6"></div>

            <!-- Step 4 -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 text-black font-bold shadow-lg">
                    4
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Blessings & Payment</h3>
                    <p class="text-gray-600 text-sm">Complete payment & receive blessings.</p>
                </div>
            </div>

            <div class="border-l-2 border-dashed border-yellow-400 h-10 ml-6"></div>

            <!-- Step 5 -->
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-yellow-400 text-black font-bold shadow-lg">
                    5
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-800">Receive receipts</h3>
                    <p class="text-gray-600 text-sm">Get reciept on WhatsApp & mail.</p>
                </div>
            </div>
        </div>

        <!-- Video/Right Side -->
        <div class="flex justify-center">
        <div class="w-full md:w-4/5 aspect-video bg-gray-200 rounded-lg shadow-lg overflow-hidden">
            <iframe
                class="w-full h-full"
                src="https://www.youtube.com/embed/wDchsz8nmbo"
                title="YouTube video player"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen>
            </iframe>

        </div>
    </div>
    </div>
</section>

    <div class="mb-12 px-4">
        <h2 class="text-xl font-semibold mb-4 text-center text-blue-800">Latest Updates</h2>
        <div class="vertical-panel">
            <div class="vertical-scroll">
                {{-- Check if there are any updates to show --}}
                @if (isset($latestUpdates) && $latestUpdates->isNotEmpty())
                    {{-- Loop through each update from the database --}}
                    @foreach ($latestUpdates as $update)
                        <div class="notification-item">{{ $update->message }}</div>
                    @endforeach
                @else
                    <div class="notification-item">No updates at the moment.</div>
                @endif
            </div>
        </div>
    </div>
<!-- Spiritual Guidance Section -->
<section style="position: relative; background: url('https://img1.hotstarext.com/image/upload/f_auto/sources/r1/cms/prod/9460/1379460-i-2b70cca05890') center/cover no-repeat; padding: 80px 15px; text-align: center; font-family: 'Poppins', sans-serif;">

    <!-- Dark overlay for readability -->
    <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.65);"></div>

    <!-- Content box -->
    <div style="position: relative; z-index:2; max-width:650px; margin:0 auto; background: rgba(255,255,255,0.06); padding:40px 25px; border-radius:14px; backdrop-filter: blur(6px); box-shadow:0 6px 20px rgba(0,0,0,0.4);">

        <h2 style="font-size:26px; color:#facc15; font-weight:700; margin-bottom:16px; text-transform:uppercase; letter-spacing:0.5px;">
            Confused About Which Puja to Choose?
        </h2>

        <p style="font-size:16px; color:#fff; margin-bottom:30px; line-height:1.6;">
            Whether it’s health, prosperity, or spiritual upliftment, our priests and experts are here<br>
            to guide you in selecting the perfect ritual for your divine journey.
        </p>

        <a href="{{ route("info.contact") }}"
           style="background: linear-gradient(135deg, #facc15, #f59e0b); color:#000; padding:12px 28px; font-size:16px; font-weight:600; border-radius:30px; text-decoration:none; box-shadow:0 4px 15px rgba(250,204,21,0.35); transition: all 0.3s ease-in-out;">
            Get Divine Guidance
        </a>
    </div>
</section>
@endsection
<style>
    .vertical-panel {
        width: 100%;
        max-width: 500px;
        height: 200px;
        margin: 0 auto;
        overflow: hidden;
        background-color: #fff8dc;
        border: 1px solid #ffd966;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
        position: relative;
    }

    .vertical-scroll {
        display: flex;
        flex-direction: column;
        animation: scroll-up 30s linear infinite;
        /* Slower scroll */
        /* animation-play-state: running ensures it never stops */
    }

    .notification-item {
        display: block;
        text-decoration: none;
        padding: 12px 0;
        font-weight: 500;
        color: #7c4a03;
        font-size: 16px;
        text-align: center;
    }

    .notification-item:hover {
        background-color: rgba(124, 74, 3, 0.1);
    }

    @keyframes scroll-up {
        0% {
            transform: translateY(100%);
        }

        100% {
            transform: translateY(-100%);
        }
    }
    .why-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    align-self: stretch;
    gap: 16px;
    border-radius: 23.666px;
    background: #c5bba2;
    padding: 32px;
    min-width: 250px;
    flex: 1;
    transition: all 0.3s ease-in-out;
    text-align: center;
}

.why-card:hover {
    background: #b3a98d;
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}
.service-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    border-radius: 20px;
    background: #ffffff;
    padding: 28px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    background: #f9f9f9;
}

</style>
