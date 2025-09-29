@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Send Announcement</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                
                {{-- (NEW) Dropdown to select target audience --}}
                <div class="mb-3">
                    <label for="target_role" class="form-label">Send To:</label>
                    <select name="target_role" id="target_role" class="form-select @error('target_role') is-invalid @enderror" required>
                        <option value="">-- Select an Audience --</option>
                        <option value="user" {{ old('target_role') == 'user' ? 'selected' : '' }}>All Users</option>
                        <option value="temple_manager" {{ old('target_role') == 'temple_manager' ? 'selected' : '' }}>All Temple Managers</option>
                        <option value="hotel_manager" {{ old('target_role') == 'hotel_manager' ? 'selected' : '' }}>All Hotel Managers</option>
                    </select>
                    @error('target_role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label">Notification Message</label>
                    <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="5" required minlength="10" placeholder="Type your announcement here...">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection