@extends('layouts.app')

@section('content')
<style>
    .lightbox-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.9);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        padding: 20px;
    }

    .lightbox-image-box {
    width: 80vw;
    height: 80vh;
    position: relative;
    display: flex;
    /* ADD THIS LINE 👇 */
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    border-radius: 12px;
    overflow: hidden;
}

    .lightbox-image {
        width: 100%;
        height: 100%;
        object-fit: cover; 
        object-position: center; 
    }

    .lightbox-close {
        position: absolute;
        top: 10px;
        right: 10px;
        color: white;
        font-size: 30px;
        background: rgba(0, 0, 0, 0.5);
        padding: 5px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 9999;
    }
    @media (max-width: 768px) {
        .lightbox-image-box {
            width: 90vw;
            height: 70vh;
        }
    }

    @media (min-width: 1024px) {
        .lightbox-image-box {
            width: 70vw;
            height: 70vh;
        }
    }
    .swiper-slide {
    height: auto;
}

</style>

<div x-data="{
        lightBoxOpen: false,
        lightBoxImage: '',
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
     <div class="bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
    <div>
        <!-- Hotel Name -->
        <h1 class="text-3xl font-bold mb-2 text-gray-700 dark:text-white">
            {{ $hotel->name }}
        </h1>

        <!-- Hotel Location -->
        <p class="text-gray-500 dark:text-white mb-6">
            {{ $hotel->location }}
        </p>
    </div>

    @if($hotel->reviews->count() > 0)
    <div class="mt-3 md:mt-0 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-4 py-2 rounded-full text-sm font-semibold flex items-center">
        <i class="fas fa-star mr-2 text-yellow-500"></i>
        {{ $averageRating }} / 5 ({{ $hotel->reviews->count() }} reviews)
    </div>
    @endif
</div>
    {{-- IMAGE GALLERY --}}
    <div class="mb-6">
        {{-- Desktop Grid Gallery --}}
        <div class="hidden lg:grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <img @click="lightBoxOpen = true; lightBoxImage = '{{ $hotel->image ? asset('storage/' . $hotel->image) : '' }}'"
                     src="{{ $hotel->image ? asset('storage/' . $hotel->image) : 'https://placehold.co/800x600/e2e8f0/475569?text=Main+Photo' }}"
                     class="w-full h-[450px] object-cover rounded-xl shadow-lg cursor-pointer hover:opacity-95 transition" />
            </div>
            <div class="space-y-4">
                @foreach($hotel->images->take(2) as $image)
                    <img @click="lightBoxOpen = true; lightBoxImage = '{{ asset('storage/' . $image->path) }}'"
                         src="{{ asset('storage/' . $image->path) }}"
                         class="w-full h-[217px] object-cover rounded-xl shadow-lg cursor-pointer hover:opacity-95 transition" />
                @endforeach
            </div>
        </div>
        {{-- Mobile Swiper Slider --}}
        <div class="lg:hidden">
            <div class="swiper mobile-gallery-swiper rounded-xl shadow-lg">
                <div class="swiper-wrapper">
                    @if($hotel->image)
                        <div class="swiper-slide">
                            <img @click="lightBoxOpen = true; lightBoxImage = '{{ asset('storage/' . $hotel->image) }}'"
                                 src="{{ asset('storage/' . $hotel->image) }}" class="w-full h-[300px] object-cover" />
                        </div>
                    @endif
                    @foreach($hotel->images as $image)
                        <div class="swiper-slide">
                            <img @click="lightBoxOpen = true; lightBoxImage = '{{ asset('storage/' . $image->path) }}'"
                                 src="{{ asset('storage/' . $image->path) }}" class="w-full h-[300px] object-cover" />
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        {{-- Lightbox with a SIMPLIFIED Swiper for better UX --}}
    <div x-show="lightBoxOpen" x-transition @keydown.escape.window="lightBoxOpen = false"
        class="lightbox-container">

        {{-- The container now simply centers the swiper --}}
        <div class="lightbox-image-box" @click.away="lightBoxOpen = false">
            <div class="swiper main-swiper w-full h-full">
                <div class="swiper-wrapper">
                    @if($hotel->image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $hotel->image) }}"
                                class="w-full h-full object-contain" />
                        </div>
                    @endif
                    @foreach($hotel->images as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset('storage/' . $image->path) }}"
                                class="w-full h-full object-contain" />
                        </div>
                    @endforeach
                </div>
                {{-- Navigation buttons --}}
                <div class="swiper-button-next text-white drop-shadow-lg"></div>
                <div class="swiper-button-prev text-white drop-shadow-lg"></div>
            </div>
        </div>

        {{-- Close Button --}}
        <button @click="lightBoxOpen = false" class="absolute top-4 right-4 text-white text-4xl z-[10001]">&times;</button>
    </div>
    </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-12">
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">About this property</h2>
                <div class="text-gray-600 leading-relaxed">{!! $hotel->description !!}</div>
            </div>
            @if($hotel->latitude && $hotel->longitude)
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">Location</h2>
                <div id="location-map" style="height: 350px; border-radius: 12px;"></div>
            </div>
            @endif
            @if($hotel->amenities->isNotEmpty())
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">Amenities</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-gray-700">
                    @foreach($hotel->amenities as $amenity)
                        <div class="flex items-center"><i class="{{ $amenity->icon }} text-indigo-500 mr-2 fa-fw"></i><span>{{ $amenity->name }}</span></div>
                    @endforeach
                </div>
            </div>
            @endif
            @if(!empty($hotel->policies) && is_array($hotel->policies))
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">Policies</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    @foreach($hotel->policies as $policy)
                        <!-- <li>{{ $policy }}</li> -->
                        <div class="text-gray-600 leading-relaxed">{!!  $policy !!}</div>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($hotel->nearby_attractions) && is_array($hotel->nearby_attractions))
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">Nearby Attractions</h2>
                <ul class="list-disc pl-6 text-gray-600 space-y-2">
                    @foreach($hotel->nearby_attractions as $attraction)
                        <!-- <li>{{ $attraction }}</li> -->
                        <div class="text-gray-600 leading-relaxed">{!!  $attraction !!}</div>
                    @endforeach
                </ul>
            </div>
            @endif
            <!-- <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2 text-gray-800">Guest Reviews</h2>
                @forelse($hotel->reviews as $review)
                    <div class="border-b border-gray-200 py-4 last:border-b-0">
                        <div class="flex items-center mb-2">
                            <span class="font-bold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</span>
                            <span class="text-xs text-gray-500 ml-auto">{{ $review->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center text-yellow-500 mb-2">
                            @for ($i = 0; $i < $review->rating; $i++) <i class="fas fa-star text-sm"></i> @endfor
                            @for ($i = $review->rating; $i < 5; $i++) <i class="far fa-star text-sm"></i> @endfor
                        </div>
                        <p class="text-gray-600">"{{ $review->comment }}"</p>
                    </div>
                @empty
                    <p class="text-gray-500">This hotel has not been reviewed yet.</p>
                @endforelse
            </div> -->
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200">
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Guest Reviews</h2>

            @if($hotel->reviews->isNotEmpty())
                <div class="swiper review-swiper">
                    <div class="swiper-wrapper">
                        @foreach($hotel->reviews as $review)
                            <div class="swiper-slide">
                                <div class="h-full bg-white border border-gray-200 rounded-lg p-5 flex flex-col shadow-sm">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-md font-semibold text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</h4>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center text-yellow-500 mb-2">
                                        @for ($i = 0; $i < $review->rating; $i++)
                                            <i class="fas fa-star text-sm"></i>
                                        @endfor
                                        @for ($i = $review->rating; $i < 5; $i++)
                                            <i class="far fa-star text-sm"></i>
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 text-sm flex-grow">"{{ $review->comment }}"</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Navigation -->
                    <div class="swiper-button-next text-gray-600"></div>
                    <div class="swiper-button-prev text-gray-600"></div>
                    <div class="swiper-pagination mt-4"></div>
                </div>
            @else
                <p class="text-gray-500">This hotel has not been reviewed yet.</p>
            @endif
        </div>
        </div>
       <div class="lg:sticky top-24 h-fit space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 text-center">Available Rooms</h2>
                <div class="grid gap-4">
                    @forelse($hotel->rooms as $room)
                        <div @click="
                                roomModal = true;
                                selectedRoom = {{ Js::from($room) }};
                                initRoomSwiper()
                             "
                             class="border border-gray-200 rounded-lg p-4 hover:border-indigo-500 hover:shadow-md transition cursor-pointer">
                            <h3 class="font-bold text-lg text-gray-900">{{ $room->type }}</h3>
                            <p class="text-sm text-gray-500">Capacity: {{ $room->capacity }} guests</p>
                            <p class="text-xl font-semibold text-indigo-600 mt-2">
                                ₹{{ number_format($room->price_per_night, 2) }}
                                <span class="text-sm text-gray-500 font-normal">/ night</span>
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No rooms have been listed for this hotel yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- RESTRUCTURED Room Details Modal --}}
    <div x-show="roomModal" x-cloak
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-[10000] p-4"
         @keydown.escape.window="roomModal = false">

        {{-- Main modal container uses grid for layout --}}
        <div class="bg-white rounded-xl shadow-lg w-full max-w-5xl relative border border-gray-300 grid grid-cols-1 md:grid-cols-2 max-h-[90vh] overflow-hidden"
             @click.away="roomModal = false">

            {{-- Left Column: Photo Gallery --}}
            <div class="relative h-64 md:h-full bg-gray-100">
                <div class="swiper room-photo-swiper w-full h-full">
                    <div class="swiper-wrapper">
                        <template x-for="photo in selectedRoom.photos" :key="photo.id">
                            <div class="swiper-slide">
                                <img :src="'/storage/' + photo.path" class="w-full h-full object-cover">
                            </div>
                        </template>
                        <template x-if="!selectedRoom.photos || selectedRoom.photos.length === 0">
                             <div class="swiper-slide bg-gray-200 flex items-center justify-center">
                                 <span class="text-gray-500">No Image Available</span>
                             </div>
                        </template>
                    </div>
                    <div class="swiper-button-next text-gray-800"></div>
                    <div class="swiper-button-prev text-gray-800"></div>
                </div>
            </div>

            {{-- Right Column: Details --}}
            <div class="flex flex-col relative max-h-[90vh] overflow-hidden">
                <button @click="roomModal = false" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-3xl z-20">&times;</button>

                {{-- Details Body (Scrollable) --}}
                <div class="p-6 overflow-y-auto">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2" x-text="selectedRoom.type"></h2>

                    <div class="flex flex-wrap items-center text-gray-600 gap-x-6 gap-y-2 mb-4">
                         <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Capacity: <strong class="text-gray-800" x-text="selectedRoom.capacity"></strong> guests</span>
                        </div>
                        <template x-if="selectedRoom.room_size">
                            <div class="flex items-center">
                                <i class="fas fa-ruler-combined mr-2"></i>
                                <span>Size: <strong class="text-gray-800"><span x-text="selectedRoom.room_size"></span> sq. ft.</strong></span>
                            </div>
                        </template>
                    </div>

                    <template x-if="selectedRoom.description">
                         <p class="text-gray-700 mt-4 mb-6 border-t border-gray-200 pt-4" x-text="selectedRoom.description"></p>
                    </template>

                    <template x-if="selectedRoom.facilities && JSON.parse(selectedRoom.facilities).length > 0">
                        <div class="border-t border-gray-200 pt-4">
                             <h3 class="text-lg font-semibold text-gray-800 mb-3">What this room offers</h3>
                             <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 text-gray-700">
                                <template x-for="facility in JSON.parse(selectedRoom.facilities)" :key="facility">
                                    <div class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-2 text-sm"></i>
                                        <span x-text="facility"></span>
                                    </div>
                                </template>
                            </div>

                        </div>
                    </template>
                </div>

                
                <div class="p-6 mt-auto border-t border-gray-200 bg-white shrink-0">
                    <div class="flex justify-between items-center">
                         <p class="text-indigo-600 text-2xl font-semibold">
                            ₹<span x-text="Number(selectedRoom.price_per_night).toLocaleString()"></span>
                            <span class="text-sm text-gray-500 font-normal">/ night</span>
                        </p>
                         <a :href="'/stays/room/' + selectedRoom.id" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
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
    var mainSwiper = new Swiper(".main-swiper", {
        loop: true, 
        spaceBetween: 20,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        grabCursor: true,
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.review-swiper', {
        slidesPerView: 1.1,
        spaceBetween: 16,
        loop: false,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 1.3,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });
});
</script>
@endif
@endpush
