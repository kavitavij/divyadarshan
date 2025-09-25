@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Manager: {{ $manager->name }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.managers.update', $manager->id) }}" method="POST" x-data="{ role: '{{ old('role', $manager->role ?? 'temple_manager') }}' }">
            @csrf
            @method('PUT')
            
            {{-- Name, Email, Password fields ... --}}
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $manager->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $manager->email) }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <hr>
            <p class="text-muted">Leave the password fields blank to keep the current password.</p>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                </div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" id="role" class="form-select" x-model="role" required>
                    <option value="temple_manager" {{ old('role', $manager->role) == 'temple_manager' ? 'selected' : '' }}>Temple Manager</option>
                    <option value="hotel_manager" {{ old('role', $manager->role) == 'hotel_manager' ? 'selected' : '' }}>Hotel Manager</option>
                </select>
            </div>

            <div class="mb-3" x-show="role === 'hotel_manager'" x-transition>
                <label for="hotel_id" class="form-label">Assign to Hotel</label>
                <select name="hotel_id" id="hotel_id" class="form-select @error('hotel_id') is-invalid @enderror">
                    <option value="">Select a Hotel</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" @if(old('hotel_id', $manager->hotel->id ?? '') == $hotel->id) selected @endif>{{ $hotel->name }}</option>
                    @endforeach
                </select>
                @error('hotel_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3" x-show="role === 'temple_manager'" x-transition>
                <label for="temple_id" class="form-label">Assign to Temple</label>
                <select name="temple_id" id="temple_id" class="form-select @error('temple_id') is-invalid @enderror">
                    <option value="">Select a Temple</option>
                    @foreach($temples as $temple)
                        <option value="{{ $temple->id }}" @if(old('temple_id', $manager->temple->id ?? '') == $temple->id) selected @endif>{{ $temple->name }}</option>
                    @endforeach
                </select>
                @error('temple_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            
            <div class="text-end">
                <a href="{{ route('admin.managers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Manager</button>
            </div>
        </form>
    </div>
</div>
@endsection