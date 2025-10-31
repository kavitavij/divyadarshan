@extends('layouts.admin')

@section('title', 'Create Announcement')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Create Announcement</h1>
                <p class="text-muted">Send targeted notifications to different user groups on the platform.</p>
            </div>
        </div>

        <style>
            /* Announcement card visuals */
            .announcement-card {
                border: 1px solid #343a40 !important;
                /* stronger, professional dark border */
                background-color: rgba(0, 0, 0, 0.03);
                /* very light dark tint */
            }

            .announcement-card .card-header {
                background-color: rgba(0, 0, 0, 0.04);
                border-bottom: 1px solid rgba(0, 0, 0, 0.22);
            }

            .announcement-card .form-label {
                color: #222;
                font-weight: 600;
            }

            .announcement-card .form-text {
                color: rgba(0, 0, 0, 0.6);
            }

            .announcement-card .form-control,
            .announcement-card .form-select {
                background-color: #fff;
            }

            @media (prefers-color-scheme: dark) {
                .announcement-card {
                    background-color: rgba(255, 255, 255, 0.02);
                    border-color: rgba(255, 255, 255, 0.08);
                }

                .announcement-card .card-header {
                    background-color: rgba(255, 255, 255, 0.03);
                }
            }
        </style>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card shadow-sm announcement-card">
            <div class="card-header">
                <h5 class="mb-0">Compose Notification</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.announcements.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="target_role" class="form-label fw-bold small">Target Audience</label>
                        <select name="target_role" id="target_role"
                            class="form-select form-select-sm @error('target_role') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Select recipients --</option>
                            <option value="user" {{ old('target_role') == 'user' ? 'selected' : '' }}>All Registered Users
                                (Devotees)</option>
                            <option value="temple_manager" {{ old('target_role') == 'temple_manager' ? 'selected' : '' }}>
                                All Temple Managers</option>
                            <option value="hotel_manager" {{ old('target_role') == 'hotel_manager' ? 'selected' : '' }}>All
                                Hotel Managers</option>
                        </select>
                        <small class="form-text text-muted small">Notification shows in recipients' dashboards.</small>
                        @error('target_role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label fw-bold small">Notification Message</label>
                        <textarea name="message" id="message" class="form-control form-control-sm @error('message') is-invalid @enderror"
                            rows="4" required minlength="10" placeholder="E.g. Temple closed for maintenance on 25th">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card-footer bg-transparent text-end border-0 px-0">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Send Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
