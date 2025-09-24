@extends('layouts.hotel-manager')

@section('content')
<style>
    .rooms-container { max-width: 1200px; margin: 20px auto; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .btn-add-room { background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
    .room-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; display: flex; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .room-card-photos { flex: 0 0 200px; }
    .room-card-photos img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px 0 0 8px; }
    .room-card-details { padding: 15px; flex-grow: 1; }
    .room-card-header { display: flex; justify-content: space-between; align-items: flex-start; }
    .room-card-title { font-size: 1.2rem; font-weight: bold; margin: 0; }
    .room-card-price { text-align: right; }
    .price-original { text-decoration: line-through; color: #999; font-size: 0.9em; }
    .price-final { font-weight: bold; color: #e74c3c; font-size: 1.1em; }
    .discount-badge { background: #e74c3c; color: white; padding: 2px 6px; font-size: 0.8em; border-radius: 4px; }
    .room-card-info { margin-top: 10px; color: #555; }
    .room-card-actions { margin-top: 15px; display: flex; gap: 10px; align-items:center; }
    .btn { padding: 5px 10px; border-radius: 5px; text-decoration: none; border: 1px solid transparent; }
    .btn-edit { background-color: #007bff; color: white; }
    .btn-delete { background-color: #dc3545; color: white; border:none; cursor:pointer; font-family: inherit; font-size: inherit; }
    /* Toggle Switch */
    .switch{position:relative;display:inline-block;width:50px;height:24px}
    .switch input{opacity:0;width:0;height:0}
    .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;transition:.4s;border-radius:24px}
    .slider:before{position:absolute;content:"";height:18px;width:18px;left:3px;bottom:3px;background-color:white;transition:.4s;border-radius:50%}
    input:checked+.slider{background-color:#28a745}
    input:checked+.slider:before{transform:translateX(26px)}
</style>

<div class="rooms-container">
    <div class="page-header">
        <h1>Manage Rooms for {{ $hotel->name }}</h1>
        <a href="{{ route('hotel-manager.rooms.create') }}" class="btn-add-room">Add New Room</a>
    </div>

    @foreach ($rooms as $room)
        <div class="room-card">
            <div class="room-card-photos">
                <img src="{{ $room->photos->first() ? asset('storage/' . $room->photos->first()->path) : 'https://via.placeholder.com/200' }}" alt="{{ $room->type }}">
            </div>
            <div class="room-card-details">
                <div class="room-card-header">
                    <div>
                        <h2 class="room-card-title">{{ $room->type }}</h2>
                        <small>Capacity: {{ $room->capacity }} People | Total Rooms: {{ $room->total_rooms }}</small>
                    </div>
                    <div class="room-card-price">
                        @if($room->discount_percentage > 0)
                            <span class="price-original">₹{{ number_format($room->price_per_night, 2) }}</span>
                            <span class="price-final">₹{{ number_format($room->discounted_price, 2) }}</span>
                            <span class="discount-badge">{{ $room->discount_percentage }}% OFF</span>
                        @else
                            <span class="price-final">₹{{ number_format($room->price_per_night, 2) }}</span>
                        @endif
                        <div>per night</div>
                    </div>
                </div>
                <div class="room-card-info">
                    {{ \Illuminate\Support\Str::limit($room->description, 150) }}
                </div>
                <div class="room-card-actions">
                    <form action="{{ route('hotel-manager.rooms.toggleVisibility', $room) }}" method="POST" style="margin:0;">
                        @csrf
                        @method('PATCH')
                        <label class="switch">
                            <input type="checkbox" {{ $room->is_visible ? 'checked' : '' }} onchange="this.form.submit()">
                            <span class="slider"></span>
                        </label>
                    </form>
                    <span>{{ $room->is_visible ? 'Visible' : 'Hidden' }}</span>
                    <a href="{{ route('hotel-manager.rooms.edit', $room->id) }}" class="btn btn-edit">Edit</a>
                    <form action="{{ route('hotel-manager.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this room?');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        {{ $rooms->links() }}
    </div>
</div>
@endsection
