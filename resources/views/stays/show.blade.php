@extends('layouts.app')

@section('content')
{{-- We add an init function to our Alpine component to initialize the room's photo slider --}}
<div x-data="{
        lightboxOpen: false,
        lightboxImage: '',
        roomModal: false,
        selectedRoom: {},
        initRoomSwiper() {
            // A short delay ensures the modal is visible before the slider is created
            setTimeout(() => {
                // Destroy any existing swiper instance to prevent errors
                if (this.swiper) {
                    this.swiper.destroy(true, true);
                }
                this.swiper = new Swiper('.room-photo-swiper', {
                    loop: true,
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    grabCursor: true,
                });
            }, 50);
        }
    }"
    class="container mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-white">{{ $hotel->name }}</h1>
            <p class="text-gray-400 mb-6">{{ $hotel->location }}</p>
        </div>
        @if($hotel->reviews->count() > 0)
        <div class="mt-3 md:mt-0 bg-green-900/50 border border-green-700 text-green-300 px-4 py-2 rounded-full text-sm font-semibold flex items-center">
            <i class="fas fa-star mr-2 text-yellow-400"></i> {{ $averageRating }} / 5 ({{ $hotel->reviews->count() }} reviews)
        </div>
        @endif
    </div>

    {{-- IMAGE GALLERY --}}
    <div>
        <div class="grid grid-cols-3 gap-4 mb-6">
            {{-- Main Large Photo --}}
            <div class="col-span-2">
                <img @click="lightboxOpen = true; lightboxImage = '{{ $hotel->image ? asset('storage/' . $hotel->image) : '' }}'"
                     src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/800x600/1a1a1a/444444?text=Main+Photo' }}"
                     class="w-full h-[450px] object-cover rounded-xl shadow cursor-pointer" />
            </div>

            {{-- Two Small Photos --}}
            <div class="flex flex-col gap-4">
                @foreach($hotel->images->take(2) as $image)
                    <img @click="lightboxOpen = true; lightboxImage = '{{ asset('storage/' . $image->path) }}'"
                         src="{{ asset('storage/' . $image->path) }}"
                         class="w-full h-[215px] object-cover rounded-xl shadow cursor-pointer hover:opacity-90 transition" />
                @endforeach
                @for($i = $hotel->images->count(); $i < 2; $i++)
                    <img src="https://placehold.co/400x300/1a1a1a/444444?text=More+Photos"
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
            <button @click="lightboxOpen = false" class="absolute top-4 right-4 text-white text-4xl">&times;</button>
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
            @if(!empty($hotel->policies) && is_array($hotel->policies))
            <div class="bg-[#1a1a1a] p-6 rounded-xl shadow-md border border-gray-800">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-700 pb-2 text-white">Policies</h2>
                <ul class="list-disc pl-6 text-gray-400 space-y-2">
                    @foreach($hotel->policies as $policy)
                        <li>{{ $policy }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($hotel->nearby_attractions) && is_array($hotel->nearby_attractions))
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

       <div class="space-y-6">
            <h2 class="text-2xl font-bold text-yellow-400 mb-4">Available Rooms</h2>
            <div class="grid gap-6">
                @forelse($hotel->rooms as $room)
                    <div @click="
                            roomModal = true;
                            selectedRoom = {{ Js::from($room) }};
                            initRoomSwiper()
                         "
                        class="border border-gray-700 rounded-lg p-5 hover:border-yellow-500 transition cursor-pointer">
                        <h3 class="font-bold text-lg text-white">{{ $room->type }}</h3>
                        <p class="text-sm text-gray-400">Capacity: {{ $room->capacity }} guests</p>
                        <p class="text-xl font-semibold text-yellow-400 mt-2">
                            ₹{{ number_format($room->price_per_night, 2) }}
                            <span class="text-sm text-gray-500">/ night</span>
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No rooms have been listed for this hotel yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- RESTRUCTURED Room Details Modal --}}
    <div x-show="roomModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[10000] p-4"
         @keydown.escape.window="roomModal = false">

        {{-- Main modal container uses grid for layout --}}
        <div class="bg-[#1a1a1a] rounded-xl shadow-lg w-full max-w-5xl relative border border-gray-700 grid grid-cols-1 md:grid-cols-2 max-h-[90vh] overflow-hidden"
             @click.away="roomModal = false">

            {{-- Left Column: Photo Gallery --}}
            <div class="relative h-64 md:h-full">
                <div class="swiper room-photo-swiper w-full h-full">
                    <div class="swiper-wrapper">
                        <template x-for="photo in selectedRoom.photos" :key="photo.id">
                            <div class="swiper-slide">
                                <img :src="'/storage/' + photo.path" class="w-full h-full object-cover">
                            </div>
                        </template>
                        <template x-if="!selectedRoom.photos || selectedRoom.photos.length === 0">
                             <div class="swiper-slide bg-gray-800 flex items-center justify-center">
                                <span class="text-gray-500">No Image Available</span>
                            </div>
                        </template>
                    </div>
                    <div class="swiper-button-next text-white"></div>
                    <div class="swiper-button-prev text-white"></div>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="flex flex-col relative max-h-[90vh] overflow-hidden">
                <button @click="roomModal = false" class="absolute top-4 right-4 text-gray-400 hover:text-white text-3xl z-20">&times;</button>

                {{-- Details Body (Scrollable) --}}
                <div class="p-6 overflow-y-auto">
                    <h2 class="text-3xl font-bold text-white mb-2" x-text="selectedRoom.type"></h2>

                    <div class="flex flex-wrap items-center text-gray-400 gap-x-6 gap-y-2 mb-4">
                         <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Capacity: <strong class="text-white" x-text="selectedRoom.capacity"></strong> guests</span>
                        </div>
                        <template x-if="selectedRoom.room_size">
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined mr-2"></i>
                                <span>Size: <strong class="text-white"><span x-text="selectedRoom.room_size"></span> sq. ft.</strong></span>
                            </div>
                        </template>
                    </div>

                    <template x-if="selectedRoom.description">
                         <p class="text-gray-300 mt-4 mb-6 border-t border-gray-800 pt-4" x-text="selectedRoom.description"></p>
                    </template>

                    <template x-if="selectedRoom.facilities && JSON.parse(selectedRoom.facilities).length > 0">
                        <div class="border-t border-gray-800 pt-4">
                             <h3 class="text-lg font-semibold text-white mb-3">What this room offers</h3>
                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-gray-300">
                                <template x-for="facility in JSON.parse(selectedRoom.facilities)" :key="facility">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-400 mr-2 text-sm"></i>
                                        <span x-text="facility"></span>
                                    </div>
                                </template>
                             </div>
                        </div>
                    </template>
                </div>

                {{-- Details Footer (Fixed) --}}
                <div class="p-6 mt-auto border-t border-gray-800 bg-[#1a1a1a] shrink-0">
                    <div class="flex justify-between items-center">
                         <p class="text-yellow-400 text-2xl font-semibold">
                            ₹<span x-text="Number(selectedRoom.price_per_night).toLocaleString()"></span>
                            <span class="text-sm text-gray-500">/ night</span>
                        </p>
                         <a :href="'/stays/room/' + selectedRoom.id" class="bg-yellow-500 hover:bg-yellow-400 text-black font-semibold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
    const hotelName = @json($hotel->name);

    const map = L.map('location-map', { scrollWheelZoom: false }).setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
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