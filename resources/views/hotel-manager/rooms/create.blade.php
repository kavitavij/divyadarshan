@extends('layouts.hotel-manager')

@section('content')
<style>
    .room-form-wrapper{max-width:760px;margin:28px auto;background:#fff;border-radius:12px;box-shadow:0 6px 16px rgba(0,0,0,.08)}
    .room-form-header{padding:22px 26px;border-bottom:1px solid #eee}
    .room-form-header h1{font-size:22px;margin:0;color:#2c3e50}
    .room-form-body{padding:24px 26px}
    .field{margin-bottom:18px}
    .field label{display:block;font-weight:600;color:#34495e;margin-bottom:6px}
    .control{width:100%;padding:10px 12px;border:1px solid #cfd6de;border-radius:8px;transition:border-color .2s, box-shadow .2s;font-size:15px}
    .control:focus{outline:0;border-color:#4b6cb7;box-shadow:0 0 0 3px rgba(75,108,183,.15)}
    .row-3{display:flex;gap:16px}
    .row-3 .field{flex:1}
    textarea.control{resize:vertical;min-height:90px}
    .actions{display:flex;gap:10px;margin-top:8px}
    .btn{border:none;border-radius:8px;padding:10px 18px;font-weight:600;cursor:pointer}
    .btn-primary{background:#4b6cb7;color:#fff}
    .btn-primary:hover{background:#3f5ca6}
    .btn-secondary{background:#eef1f6;color:#2c3e50}
    .btn-secondary:hover{background:#e3e7ef}
    .alert{border-radius:8px;padding:10px 14px;margin-bottom:16px}
    .alert-danger{background:#ffe9e9;color:#9c1c1c}

    /* New Styles for Photo Upload and Facilities */
    .photo-uploader { border: 2px dashed #cfd6de; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; }
    .photo-uploader:hover { border-color: #4b6cb7; }
    .photo-uploader .upload-text { font-weight: 600; color: #34495e; }
    .photo-preview { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .photo-preview img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
    .facilities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
    .facility-item { display: flex; align-items: center; }
    .facility-item input { margin-right: 8px; }
</style>

<div class="room-form-wrapper">
    <div class="room-form-header">
        <h1>Add New Room @isset($hotel) to {{ $hotel->name }} @endisset</h1>
    </div>

    <div class="room-form-body">
        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the errors below.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- IMPORTANT: Add enctype for file uploads --}}
        <form action="{{ route('hotel-manager.rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Room Photos --}}
            <div class="field">
                <label for="photos">Room Photos</label>
                <div class="photo-uploader" onclick="document.getElementById('photos').click()">
                    <span class="upload-text">Click to select images</span>
                    <input type="file" name="photos[]" id="photos" class="control" multiple accept="image/*" style="display: none;">
                </div>
                <div class="photo-preview" id="photo-preview"></div>
                @error('photos.*') <small class="text-danger">{{ $message }}</small> @enderror
                @error('photos') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Room Type --}}
            <div class="field">
                <label for="type">Room Type</label>
                <select name="type" id="type" class="control" required>
                    <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select Room Type</option>
                    @foreach (['Standard','Deluxe','Super Deluxe','Suite','Family Room','Dormitory'] as $opt)
                        <option value="{{ $opt }}" {{ old('type') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('type') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            {{-- Capacity / Price / Total / Room Size --}}
            <div class="row-3">
                <div class="field">
                    <label for="price_per_night">Price per Night (₹)</label>
                    <input type="number" name="price_per_night" id="price_per_night" class="control" step="0.01" value="{{ old('price_per_night') }}" required>
                    @error('price_per_night') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label for="discount_percentage">Discount (Percentage)</label>
                    <input type="number" name="discount_percentage" id="discount_percentage" class="control" step="0.01" value="{{ old('discount_percentage') }}" min="0" max="100">
                    @error('discount_percentage') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
                <div class="row-3">
                    <div class="field">
                    <label for="capacity">Capacity (people)</label>
                    <input type="number" name="capacity" id="capacity" class="control" value="{{ old('capacity') }}" required>
                    @error('capacity') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label for="total_rooms">Total Rooms</label>
                    <input type="number" name="total_rooms" id="total_rooms" class="control" value="{{ old('total_rooms') }}" required>
                    @error('total_rooms') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                 <div class="field">
                    <label for="room_size">Room Size (sq. ft.)</label>
                    <input type="number" name="room_size" id="room_size" class="control" value="{{ old('room_size') }}">
                    @error('room_size') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            {{-- Description --}}
            <div class="field">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="control" rows="4">{{ old('description') }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Facilities --}}
            <div class="field">
                <label>Facilities</label>
                @php
                    $facilities = [
                    'Free toiletries', 'Toilet', 'Bath or shower', 'Hairdryer', 'Air conditioning',
                    'Safety deposit box', 'Desk', 'TV', 'Refrigerator', 'Ironing facilities',
                    'Tea/Coffee maker', 'Flat-screen TV', 'Minibar', 'Cable channels', 'Wake-up service',
                    'Alarm clock', 'Wardrobe or closet', 'Free Wifi','Balcony'
                ];
                @endphp
                <div class="facilities-grid">
                    @foreach ($facilities as $facility)
                    <div class="facility-item">
                        <input type="checkbox" name="facilities[]" value="{{ $facility }}" id="facility_{{ \Illuminate\Support\Str::slug($facility) }}" {{ is_array(old('facilities')) && in_array($facility, old('facilities')) ? 'checked' : '' }}>
                        <label for="facility_{{ \Illuminate\Support\Str::slug($facility) }}">{{ $facility }}</label>
                    </div>
                    @endforeach
                </div>
                 @error('facilities') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Actions --}}
            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Room</button>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('photos').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('photo-preview');
    previewContainer.innerHTML = ''; // Clear previous previews
    const files = event.target.files;
    if (files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
@endsection
