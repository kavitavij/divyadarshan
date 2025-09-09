@extends('layouts.hotel-manager')

@section('content')
<style>
    :root {
        --primary-color: #4f46e5;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --light-bg: #f9fafb;
        --border-color: #e5e7eb;
    }

    .rooms-container {
        background-color: var(--light-bg);
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #111827;
    }

    .btn-add-room {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--primary-color);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-add-room:hover {
        background-color: #4338ca;
    }

    .room-card {
        background-color: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 1.25rem;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .room-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: var(--primary-color);
    }
    .room-card.is-hidden {
        background-color: #f8f9fa;
        opacity: 0.8;
    }

    .room-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .room-type {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-visible {
        background-color: #dcfce7;
        color: #166534;
    }
    .status-hidden {
        background-color: #f3f4f6;
        color: #4b5563;
    }

    .room-card-body {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }
    .info-item {
        font-size: 0.9rem;
    }
    .info-label {
        color: var(--secondary-color);
        margin-bottom: 0.25rem;
        display: block;
    }
    .info-value {
        font-weight: 600;
        color: #111827;
        font-size: 1rem;
    }
    .price {
        color: var(--primary-color);
    }

    .room-card-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none !important;
    }

    .btn-edit { background-color: #e0e7ff; color: #4338ca; }
    .btn-edit:hover { background-color: #c7d2fe; }

    .btn-hide { background-color: #feefc3; color: #92400e; }
    .btn-hide:hover { background-color: #fde68a; }
    
    .btn-show { background-color: #d1fae5; color: #065f46; }
    .btn-show:hover { background-color: #a7f3d0; }

    .btn-delete { background-color: #fee2e2; color: #b91c1c; }
    .btn-delete:hover { background-color: #fecaca; }

    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border: 2px dashed var(--border-color);
        border-radius: 12px;
    }
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #374151;
    }
    .empty-state p {
        color: var(--secondary-color);
        margin-top: 0.5rem;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container-fluid rooms-container p-4">
    <div class="header-bar">
        <h1 class="header-title">Manage Rooms for {{ $hotel->name }}</h1>
        <a href="{{ route('hotel-manager.rooms.create') }}" class="btn-add-room">
            <i class="fas fa-plus"></i> Add New Room
        </a>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             class="alert-container" x-transition>
            <div class="alert alert-success shadow-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <div>
        @forelse ($rooms as $room)
        <div class="room-card {{ !$room->is_visible ? 'is-hidden' : '' }}">
            <div class="room-card-header">
                <h2 class="room-type">{{ $room->type }}</h2>
                @if ($room->is_visible)
                    <span class="status-badge status-visible">Visible</span>
                @else
                    <span class="status-badge status-hidden">Hidden</span>
                @endif
            </div>

            <div class="room-card-body">
                <div class="info-item">
                    <span class="info-label">Capacity</span>
                    <span class="info-value">{{ $room->capacity }} People</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Price / Night</span>
                    <span class="info-value price">₹{{ number_format($room->price_per_night, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Total Rooms</span>
                    <span class="info-value">{{ $room->total_rooms }}</span>
                </div>
            </div>

            <div class="room-card-actions">
                <form action="{{ route('hotel-manager.rooms.toggleVisibility', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    @if ($room->is_visible)
                        <button type="submit" class="btn-action btn-hide"><i class="fas fa-eye-slash"></i> Hide</button>
                    @else
                        <button type="submit" class="btn-action btn-show"><i class="fas fa-eye"></i> Show</button>
                    @endif
                </form>
                
                <a href="{{ route('hotel-manager.rooms.edit', $room->id) }}" class="btn-action btn-edit">
                    <i class="fas fa-pencil-alt"></i> Edit
                </a>

                <form action="{{ route('hotel-manager.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to permanently delete this room type?')">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <h3>No Rooms Found</h3>
            <p>You haven't added any room types yet. Get started by adding your first one.</p>
            <a href="{{ route('hotel-manager.rooms.create') }}" class="btn-add-room">
                + Add First Room
            </a>
        </div>
        @endforelse
    </div>
</div>
@endsection
