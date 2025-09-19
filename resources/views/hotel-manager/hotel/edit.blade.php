@extends('layouts.hotel-manager')

@section('title', 'Edit Hotel Details')

@section('content')
@push('styles')
<style>
    .search-box {
        width: 100%;
        max-width: 100%; /* makes it span the container */
        padding: 14px 18px;
        font-size: 1.1rem;
        border-radius: 10px;
        border: 1px solid #ccc;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .search-box:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
    }
</style>
@endpush

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="card border-0 shadow-xl rounded-4">
                <div class="card-header bg-gradient-primary text-white py-3 d-flex align-items-center">
                    <i class="fas fa-hotel me-2"></i>
                    <h4 class="mb-0">Edit Hotel - <strong>{{ $hotel->name }}</strong></h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('hotel-manager.hotel.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Hotel Info --}}
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i> Basic Information
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">🏨 Hotel Name</label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    value="{{ old('name', $hotel->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">📍 Location / City</label>
                                <input type="text" name="location" class="form-control form-control-lg"
                                    value="{{ old('location', $hotel->location) }}" required>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">📝 Description</label>
                            <textarea name="description"
                            class="form-control form-control-lg w-100 wysiwyg-editor"
                            rows="5"
                            placeholder="Describe your hotel in detail...">{{ old('description', $hotel->description) }}</textarea>
                        </div>
                        <hr class="my-4">

                        {{-- Policies --}}
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-clipboard-list me-2"></i> Policies
                        </h5>
                        <div class="mb-4">
                            <textarea name="policies" 
                            class="form-control form-control-lg w-100 wysiwyg-editor" 
                            placeholder="Enter one policy per line">{{ old('policies', is_array($hotel->policies) ? implode("\n", $hotel->policies) : '') }}</textarea>
                        </div>

                        {{-- Nearby Attractions --}}
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-map-marked-alt me-2"></i> Nearby Attractions
                        </h5>
                        <div class="mb-4">
                            <textarea name="nearby_attractions" 
                            class="form-control form-control-lg w-100 wysiwyg-editor"
                            placeholder="Enter one attraction per line">{{ old('nearby_attractions', is_array($hotel->nearby_attractions) ? implode("\n", $hotel->nearby_attractions) : '') }}</textarea>
                        </div>

                        <hr class="my-4">

                        {{-- Amenities --}}
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-concierge-bell me-2"></i> Amenities
                        </h5>
                        <div class="row mb-4">
                            @foreach($amenities as $amenity)
                                <div class="col-md-4 col-6 mb-2">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                            name="amenities[]" value="{{ $amenity->id }}"
                                            id="amenity-{{ $amenity->id }}"
                                            {{ in_array($amenity->id, $hotelAmenities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="amenity-{{ $amenity->id }}">
                                            <i class="{{ $amenity->icon }} text-primary me-1"></i> {{ $amenity->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">
                        {{-- Map --}}
                        <h5 class="text-primary fw-bold mb-3">
                        <i class="fas fa-map me-2"></i> Location Map</h5>
                        <p class="small text-muted mb-2">Search for a location or drag the marker to set your hotel's exact position.</p>
                        <input id="pac-input" class="form-control search-box mb-3" type="text" placeholder="🔍 Search location..."/>
                        <div id="map" style="height: 300px; border-radius: 10px; border: 1px solid #ddd;" class="mb-4"></div>
                        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $hotel->latitude) }}">
                        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $hotel->longitude) }}">
                        <h5 class="text-primary fw-bold mb-3">
                        <i class="fas fa-map me-2"></i> Location Map
                        </h5>

                        {{-- Image --}}
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-image me-2"></i> Hotel Image
                        </h5>
                        <div class="text-center mb-3">
                            @if ($hotel->image)
                                <img src="{{ asset('storage/' . $hotel->image) }}" class="rounded shadow-sm mb-3" style="max-height: 200px;" alt="{{ $hotel->name }}">
                            @else
                                <p class="text-muted fst-italic">No image uploaded yet</p>
                            @endif
                        </div>
                        <input type="file" name="image" class="form-control">

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('hotel-manager.dashboard') }}" class="btn btn-outline-secondary btn-lg px-4">
                                ⬅ Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                💾 Update Details
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- TinyMCE CDN -->
<script src="https://cdn.tiny.cloud/1/o5wfjvocpzdett1nnvnmeopwgl8i2gp5j1smdegnaukyamkf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    // Initialize TinyMCE for description box
    tinymce.init({
        selector: 'textarea.wysiwyg-editor',
        plugins: 'lists link code',
        toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
        menubar: false,
        height: 300
    });
</script>

<script>
    let map;
    let marker;
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    function initMap() {
        const initialPosition = {
            lat: parseFloat(latInput.value) || 28.6139,
            lng: parseFloat(lngInput.value) || 77.2090
        };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: initialPosition,
        });

        marker = new google.maps.Marker({
            position: initialPosition,
            map: map,
            draggable: true,
        });

        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);

        map.addListener("bounds_changed", () => {
            searchBox.setBounds(map.getBounds());
        });

        searchBox.addListener("places_changed", () => {
            const places = searchBox.getPlaces();

            if (places.length == 0) return;

            const place = places[0];
            if (!place.geometry || !place.geometry.location) return;

            map.setCenter(place.geometry.location);
            marker.setPosition(place.geometry.location);

            latInput.value = place.geometry.location.lat();
            lngInput.value = place.geometry.location.lng();
        });

        google.maps.event.addListener(marker, 'dragend', function(event) {
            latInput.value = event.latLng.lat();
            lngInput.value = event.latLng.lng();
        });
    }
    window.initMap = initMap;
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap" async defer></script>
@endpush
