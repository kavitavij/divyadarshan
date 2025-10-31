@extends('layouts.hotel-manager')

@section('title', 'All Notifications')

@push('styles')
    <style>
        .notification-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #fffbe6;
            font-weight: 500;
        }

        .notification-icon {
            font-size: 1.3rem;
            color: #ca8a04;
            flex-shrink: 0;
        }

        .notification-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .message {
            font-size: 0.95rem;
            color: #212529;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-bell me-2"></i>All Notifications</h4>
                    <form action="{{ route('hotel-manager.notifications.all') }}" method="GET"
                        class="d-flex align-items-center">
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="form-control form-control-sm me-2" />
                        <button type="submit" class="btn btn-sm btn-warning">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        @if (request('date'))
                            <a href="{{ route('hotel-manager.notifications.all') }}"
                                class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                        @endif
                    </form>
                </div>

            </div>
            <div class="card-body p-0">
                @forelse ($notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? '#' }}" class="text-decoration-none text-dark">
                        <div class="notification-item {{ is_null($notification->read_at) ? 'unread' : '' }}">
                            <i class="fas fa-info-circle notification-icon"></i>
                            <div class="notification-content d-flex justify-content-between w-100 align-items-center">
                                <div class="message flex-grow-1">
                                    {{ $notification->data['message'] }}
                                </div>
                                <div class="notification-time text-end ms-3">
                                    @if ($notification->created_at->isToday())
                                        Today at {{ $notification->created_at->format('h:i A') }}
                                    @else
                                        {{ $notification->created_at->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        You have no notifications.
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links('pagination::bootstrap-4', ['class' => 'pagination-sm']) }}
                </div>
            </div>
        </div>
    </div>
    @push('styles')
        <style>
            .pagination {
                font-size: 0.85rem;
            }

            .pagination .page-link {
                padding: 0.3rem 0.6rem;
                font-size: 0.85rem;
            }

            .pagination .page-item.active .page-link {
                background-color: #ca8a04;
                border-color: #ca8a04;
            }

            .pagination .page-link {
                border-radius: 0.25rem;
            }
        </style>
    @endpush

@endsection
