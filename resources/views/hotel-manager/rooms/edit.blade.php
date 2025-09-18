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
    .row-3{display:flex;gap:16px;flex-wrap: wrap;}
    .row-3 .field{flex:1; min-width: 150px;}
    .actions{display:flex;gap:10px;margin-top:8px}
    .btn{border:none;border-radius:8px;padding:10px 18px;font-weight:600;cursor:pointer; transition: background-color 0.2s;}
    .btn-primary{background:#4b6cb7;color:#fff}
    .btn-primary:hover{background:#3f5ca6}
    .btn-secondary{background:#eef1f6;color:#2c3e50}
    .btn-secondary:hover{background:#e3e7ef}
    .alert{border-radius:8px;padding:10px 14px;margin-bottom:16px}
    .alert-danger{background:#ffe9e9;color:#9c1c1c}
    .photo-uploader { border: 2px dashed #cfd6de; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; }
    .photo-preview, .existing-photos { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .photo-preview img, .existing-photos img { width: 100px; height: 100px; object-fit: cover; border-radius: 8px; }
    .facilities-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px; }
    .facility-item { display: flex; align-items: center; }
    .photo-wrapper { position:relative; display:inline-block; }
    .delete-photo-btn { position:absolute; top:4px; right:4px; background:rgba(239, 68, 68, 0.8); color:white; border:none; border-radius:50%; width:22px; height:22px; cursor:pointer; display:flex; justify-content: center; align-items:center; font-size:14px; line-height:1; }
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-content { background: #fff; padding: 25px; border-radius: 8px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    .btn-danger { background-color: #ef4444; color: white; }

    /* Styles for the Description Builder */
    .description-builder { border: 1px solid #cfd6de; border-radius: 8px; padding: 16px; }
    .desc-section { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 12px; margin-bottom: 12px; }
    .desc-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .desc-section-title { font-weight: 600; color: #495057; font-size: 14px; }
    .desc-section-actions .btn-sm { padding: 4px 8px; font-size: 12px; background: #dc3545; color: white; border-radius: 6px; }
    .desc-section textarea { min-height: 100px; resize: vertical; }
    .add-section-btns { display: flex; gap: 10px; margin-top: 12px; }
</style>

<div class="room-form-wrapper">
    <div class="room-form-header">
        <h1>Edit Room: {{ $room->type }}</h1>
    </div>

    <div class="room-form-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the errors below.</strong>
            </div>
        @endif

        <form action="{{ route('hotel-manager.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data"
              x-data="descriptionEditor()" @submit="prepareSubmission">
            @csrf
            @method('PUT')

            {{-- Existing Photos --}}
            <div class="field">
                <label>Existing Photos</label>
                <div class="existing-photos" id="existing-photos-container">
                    @forelse($room->photos as $photo)
                        <div class="photo-wrapper" id="photo-wrapper-{{ $photo->id }}">
                            <img src="{{ asset('storage/'.$photo->path) }}" alt="Room Photo">
                            <button type="button" class="delete-photo-btn" data-photo-id="{{ $photo->id }}">×</button>
                        </div>
                    @empty
                        <p>No photos have been uploaded for this room yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Add New Photos --}}
            <div class="field">
                <label for="photos">Add New Photos</label>
                <div class="photo-uploader" onclick="document.getElementById('photos').click()">
                    <span class="upload-text">Click to select images to add</span>
                    <input type="file" name="photos[]" id="photos" class="control" multiple accept="image/*" style="display: none;">
                </div>
                <div class="photo-preview" id="photo-preview"></div>
                @error('photos.*') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Room Type --}}
            <div class="field">
                <label for="type">Room Type</label>
                <select name="type" id="type" class="control" required>
                    @foreach (['Standard','Deluxe','Super Deluxe','Suite','Family Room','Dormitory'] as $opt)
                        <option value="{{ $opt }}" {{ old('type', $room->type) === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('type') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Capacity / Price / Total / Room Size --}}
            <div class="row-3">
                <div class="field">
                    <label for="capacity">Capacity (people)</label>
                    <input type="number" name="capacity" id="capacity" class="control" value="{{ old('capacity', $room->capacity) }}" required>
                </div>
                <div class="field">
                    <label for="price_per_night">Price per Night (₹)</label>
                    <input type="number" name="price_per_night" id="price_per_night" class="control" step="0.01" value="{{ old('price_per_night', $room->price_per_night) }}" required>
                </div>
                <div class="field">
                    <label for="total_rooms">Total Rooms</label>
                    <input type="number" name="total_rooms" id="total_rooms" class="control" value="{{ old('total_rooms', $room->total_rooms) }}" required>
                </div>
                <div class="field">
                    <label for="room_size">Room Size (sq. ft.)</label>
                    <input type="number" name="room_size" id="room_size" class="control" value="{{ old('room_size', $room->room_size) }}">
                </div>
            </div>

            {{-- Description --}}
            <div class="field">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="control" rows="4">{{ old('description', $room->description) }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>


            {{-- Facilities --}}
            <div class="field">
                <label>Facilities</label>
                @php
                $allFacilities = [
                    'Free toiletries', 'Toilet', 'Bath or shower', 'Hairdryer', 'Air conditioning',
                    'Safety deposit box', 'Desk', 'TV', 'Refrigerator', 'Ironing facilities',
                    'Tea/Coffee maker', 'Flat-screen TV', 'Minibar', 'Cable channels', 'Wake-up service',
                    'Alarm clock', 'Wardrobe or closet', 'Free Wifi'
                ];

                // Ensure $roomFacilities is always an array
                $roomFacilities = json_decode($room->facilities, true);
                if (!is_array($roomFacilities)) {
                    $roomFacilities = [];
                }
            @endphp

                <div class="facilities-grid">
                    @foreach ($allFacilities as $facility)
                    <div class="facility-item">
                        <input type="checkbox" name="facilities[]" value="{{ $facility }}" id="facility_{{ \Illuminate\Support\Str::slug($facility) }}"
                        {{ in_array($facility, old('facilities', $roomFacilities)) ? 'checked' : '' }}>
                        <label for="facility_{{ \Illuminate\Support\Str::slug($facility) }}">{{ $facility }}</label>
                    </div>
                    @endforeach
                </div>
                @error('facilities') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Update Room</button>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<!-- Custom Confirmation Modal for photo deletion -->
<div id="deleteConfirmationModal" class="modal-overlay">
    <div class="modal-content">
        <h2>Confirm Deletion</h2>
        <p>Are you sure you want to delete this photo?</p>
        <div class="modal-actions">
            <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
            <button id="confirmDelete" class="btn btn-danger">Yes, Delete</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function descriptionEditor() {
    return {
        // This line now correctly loads the existing description data from the database
        sections: {!! old('description') ? json_encode(json_decode(old('description'))) : ($room->description ? $room->description : '[]') !!},
        addSection(type) {
            this.sections.push({ type: type, content: '' });
        },
        removeSection(index) {
            this.sections.splice(index, 1);
        },
        prepareSubmission() {
            this.$refs.descriptionInput.value = JSON.stringify(this.sections);
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // New photo preview script
    const photoInput = document.getElementById('photos');
    const previewContainer = document.getElementById('photo-preview');
    if(photoInput) {
        photoInput.addEventListener('change', function(event) {
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
    }

    // Modal and photo deletion script
    const modal = document.getElementById('deleteConfirmationModal');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    let photoToDeleteId = null;

    document.querySelectorAll('.delete-photo-btn').forEach(button => {
        button.addEventListener('click', function() {
            photoToDeleteId = this.dataset.photoId;
            modal.style.display = 'flex'; // Show the modal
        });
    });

    cancelDeleteBtn.addEventListener('click', () => {
        modal.style.display = 'none'; // Hide modal
        photoToDeleteId = null;
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) { // If click is on the overlay
            modal.style.display = 'none';
            photoToDeleteId = null;
        }
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (!photoToDeleteId) return;

        const urlTemplate = "{{ route('hotel-manager.rooms.photo.delete', ['photo' => 'PLACEHOLDER']) }}";
        const url = urlTemplate.replace('PLACEHOLDER', photoToDeleteId);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
        .then(res => {
            if (!res.ok) {
               // Throw an error to be caught by the .catch block
               throw new Error(`Server responded with status: ${res.status}`);
            }
            return res.json();
        })
        .then(data => {
            if(data.success){
                document.getElementById(`photo-wrapper-${photoToDeleteId}`).remove();
            } else {
                console.error('Failed to delete photo:', data.message);
                alert('An error occurred. Please try again.');
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('A network or server error occurred.');
        })
        .finally(() => {
            modal.style.display = 'none'; // Hide modal
            photoToDeleteId = null;
        });
    });
});
</script>
@endpush
@endsection

