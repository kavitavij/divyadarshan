@extends('layouts.app')
@section('content')

<div x-data="{ lightboxOpen: false, lightboxImage: '' }" class="bg-[#0d0d0d] text-gray-300">
    <div class="container mx-auto px-4 py-10">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div>
                <h1 class="text-4xl font-extrabold text-white">{{ $hotel->name }}</h1>
                <p class="text-gray-400 mt-1">{{ $hotel->location }}</p>
            </div>
            @if($hotel->reviews->count() > 0)
            <div class="mt-3 md:mt-0 bg-green-900/50 border border-green-700 text-green-300 px-4 py-2 rounded-full text-sm font-semibold flex items-center">
                <i class="fas fa-star mr-2 text-yellow-400"></i> {{ $averageRating }} / 5 ({{ $hotel->reviews->count() }} reviews)
            </div>
            @endif
        </div>

        {{-- IMAGE GALLERY (from 2nd code) --}}
        <div>
            <div class="grid grid-cols-3 gap-4 mb-6">
                {{-- Main Large Photo --}}
                <div class="col-span-2">
                    <img @click="lightboxOpen = true; lightboxImage = '{{ $hotel->image ? asset('storage/' . $hotel->image) : '' }}'"
                         src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/800x600' }}"
                         class="w-full h-[450px] object-cover rounded-xl shadow cursor-pointer" />
                </div>

                {{-- Two Small Photos --}}
                <div class="flex flex-col gap-4">
                    @foreach($hotel->images->take(2) as $image)
                        <img @click="lightboxOpen = true; lightboxImage = '{{ asset('storage/' . $image->path) }}'"
                             src="{{ asset('storage/' . $image->path) }}"
                             class="w-full h-[215px] object-cover rounded-xl shadow cursor-pointer hover:opacity-90 transition" />
                    @endforeach

                    {{-- Placeholder if less than 2 --}}
                    @for($i = $hotel->images->count(); $i < 2; $i++)
                        <img src="https://placehold.co/400x300"
                             class="w-full h-[215px] object-cover rounded-xl shadow" />
                    @endfor
                </div>
            </div>

            {{-- Lightbox with Swiper --}}
            <div x-show="lightboxOpen" x-transition @keydown.escape.window="lightboxOpen = false"
                 class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-[9999] p-4">
                <div class="max-w-6xl w-full">
                    {{-- Main Swiper --}}
                    <div class="swiper main-swiper h-[500px] w-full rounded-xl shadow-lg">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/1200x800' }}"
                                     class="w-full h-[500px] object-cover rounded-xl" />
                            </div>
                            @foreach($hotel->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                         class="w-full h-[500px] object-cover rounded-xl" />
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>

                    {{-- Thumbnails --}}
                    <div thumbsSlider="" class="swiper thumbnail-swiper h-24 w-full mt-3">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/150x100' }}"
                                     class="w-full h-24 object-cover rounded-md cursor-pointer opacity-70 hover:opacity-100 transition" />
                            </div>
                            @foreach($hotel->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                         class="w-full h-24 object-cover rounded-md cursor-pointer opacity-70 hover:opacity-100 transition" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button @click="lightboxOpen = false"
                        class="absolute top-4 right-4 text-white text-4xl">&times;</button>
            </div>
        </div>
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">About this property</h2>
                    <p class="text-gray-300 leading-relaxed">{{ $hotel->description }}</p>
                </div>
                @if($hotel->latitude && $hotel->longitude)
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Location</h2>
                    <div id="location-map" style="height: 350px; border-radius: 12px;"></div>
                </div>
                @endif
                @if($hotel->amenities->isNotEmpty())
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Amenities</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-gray-300">
                        @foreach($hotel->amenities as $amenity)
                            <div class="flex items-center"><i class="{{ $amenity->icon }} text-yellow-400 mr-2 fa-fw"></i><span>{{ $amenity->name }}</span></div>
                        @endforeach
                    </div>
                </div>
                @endif
                @if(!empty($hotel->policies))
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Policies</h2>
                    <ul class="list-disc pl-6 text-gray-400 space-y-2">
                        @foreach($hotel->policies as $policy)
                            <li>{{ $policy }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($hotel->nearby_attractions))
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Nearby Attractions</h2>
                    <ul class="list-disc pl-6 text-gray-400 space-y-2">
                        @foreach($hotel->nearby_attractions as $attraction)
                            <li>{{ $attraction }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Guest Reviews</h2>
                    @forelse($hotel->reviews as $review)
                        <div class="border-b border-gray-700 py-4 last:border-b-0">
                            <div class="flex items-center mb-2">
                                <span class="font-bold text-white">{{ $review->user->name ?? 'Anonymous' }}</span>
                                <span class="text-xs text-gray-500 ml-auto">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center text-yellow-400 mb-2">
                                @for ($i = 0; $i < $review->rating; $i++) <i class="fas fa-star text-sm"></i> @endfor
                                @for ($i = $review->rating; $i < 5; $i++) <i class="far fa-star text-sm"></i> @endfor
                            </div>
                            <p class="text-gray-300">"{{ $review->comment }}"</p>
                        </div>
                    @empty
                        <p class="text-gray-500">This hotel has not been reviewed yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="lg:sticky top-24 h-fit">
                <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                    <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Available Rooms</h2>
                    <div class="space-y-5">
                        @forelse($hotel->rooms as $room)
                            <div class="border border-gray-700 rounded-lg p-5 hover:border-yellow-500 transition">
                                <h3 class="font-bold text-lg text-white">{{ $room->type }}</h3>
                                <p class="text-sm text-gray-400">Capacity: {{ $room->capacity }} guests</p>
                                <p class="text-xl font-semibold text-yellow-400 mt-2">
                                    ₹{{ number_format($room->price_per_night, 2) }}
                                    <span class="text-sm text-gray-500">/ night</span>
                                </p>
                                <div class="mt-4">
                                    <a href="{{ route('stays.details', $room) }}" class="w-full inline-block text-center bg-yellow-500 hover:bg-yellow-400 text-black font-semibold py-2 px-4 rounded-lg shadow">
                                        Book Now
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No rooms have been listed for this hotel yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>


        {{-- REST OF FIRST CODE (about, map, amenities, policies, reviews, rooms) --}}
        {{-- keep everything from your first code here unchanged --}}

    </div>
</div>
@endsection

@push('scripts')
{{-- Swiper JS for the gallery --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var thumbnailSwiper = new Swiper(".thumbnail-swiper", {
        spaceBetween: 10, slidesPerView: 4, freeMode: true, watchSlidesProgress: true,
        breakpoints: { 640: { slidesPerView: 6 }, 1024: { slidesPerView: 8 } }
    });
    var mainSwiper = new Swiper(".main-swiper", {
        spaceBetween: 10,
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        thumbs: { swiper: thumbnailSwiper },
    });
});
</script>

{{-- Leaflet JS for the map --}}
@if($hotel->latitude && $hotel->longitude)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const lat = {{ $hotel->latitude }};
    const lng = {{ $hotel->longitude }};
    const hotelName = "{{ Js::from($hotel->name) }}";

    const map = L.map('location-map', { scrollWheelZoom: false }).setView([lat, lng], 15);

    // Using a dark map theme to match your website
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap contributors © CARTO'
    }).addTo(map);

    const customIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
    });

    const popupContent = `
        <div style="text-align: center;">
            <div style="font-weight: bold; margin-bottom: 5px;">${hotelName}</div>
            <div style="display: flex; gap: 10px; justify-content: center; margin-top: 10px;">
                <a href="https://maps.google.com/?q=${lat},${lng}" target="_blank" style="background-color: #3B82F6; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">View Map</a>
                <a href="https://maps.google.com/?daddr=${lat},${lng}" target="_blank" style="background-color: #10B981; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">Directions</a>
            </div>
        </div>
    `;

    L.marker([lat, lng], {icon: customIcon}).addTo(map)
        .bindPopup(popupContent)
        .openPopup();

    map.on('click', function() {
        window.open(`https://maps.google.com/?q=${lat},${lng}`, '_blank');
    });
});
</script>
@endif
@endpush
