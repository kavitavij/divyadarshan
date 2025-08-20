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
            </div>
        @endif

        <form action="{{ route('hotel-manager.rooms.store') }}" method="POST">
            @csrf

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

            {{-- Capacity / Price / Total --}}
            <div class="row-3">
                <div class="field">
                    <label for="capacity">Capacity (people)</label>
                    <input type="number" name="capacity" id="capacity" class="control" value="{{ old('capacity') }}" required>
                    @error('capacity') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label for="price_per_night">Price per Night (₹)</label>
                    <input type="number" name="price_per_night" id="price_per_night" class="control" step="0.01" value="{{ old('price_per_night') }}" required>
                    @error('price_per_night') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="field">
                    <label for="total_rooms">Total Rooms</label>
                    <input type="number" name="total_rooms" id="total_rooms" class="control" value="{{ old('total_rooms') }}" required>
                    @error('total_rooms') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="field">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="control" rows="4">{{ old('description') }}</textarea>
                @error('description') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {{-- Actions --}}
            <div class="actions">
                <button type="submit" class="btn btn-primary">Save Room</button>
                <a href="{{ route('hotel-manager.rooms.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
