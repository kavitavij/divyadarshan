@extends('layouts.admin')

@section('title', 'View Manager')

@section('content')
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1>Manager Details</h1>
        <a href="{{ route('admin.managers.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to All Managers
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="card-title">{{ $manager->name }}</h3>
                    <p class="card-text text-muted">{{ $manager->email }}</p>
                    <hr>

                    <div class="mb-3">
                        <strong>Role:</strong>
                        @if ($manager->roles->isNotEmpty())
                            <span
                                class="badge bg-info">{{ Str::title(str_replace('_', ' ', $manager->roles->first()->name)) }}</span>
                        @else
                            <span class="text-muted">No role assigned</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        @if ($manager->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Manages:</strong>
                        @if ($manager->hotel)
                            <span class="fw-bold">{{ $manager->hotel->name }}</span> (Hotel)
                        @elseif ($manager->temple)
                            <span class="fw-bold">{{ $manager->temple->name }}</span> (Temple)
                        @else
                            <span class="text-muted">Not Assigned</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>Account Created:</strong>
                        {{ $manager->created_at->format('F j, Y \a\t h:i A') }}
                        ({{ $manager->created_at->diffForHumans() }})
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        {{ $manager->updated_at->format('F j, Y \a\t h:i A') }}
                        ({{ $manager->updated_at->diffForHumans() }})
                    </div>

                </div>
                <div class="col-md-4 text-center align-self-center">
                    {{-- Display the manager's profile photo or a default avatar --}}
                    <img src="{{ $manager->profile_photo_url }}" alt="{{ $manager->name }}"
                        class="img-fluid rounded-circle shadow-sm mx-auto"
                        style="width: 150px; height: 150px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
@endsection
