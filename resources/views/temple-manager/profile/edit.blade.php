@extends('layouts.temple-manager')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Your Profile</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('temple-manager.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" disabled>
                            <div class="form-text">Your email address cannot be changed. Please contact the administrator to update it.</div>
                        </div>
                        
                        <hr class="my-4">
    <p class="text-muted">To change your password, please fill out all three fields below. Otherwise, leave them blank.</p>

    {{-- Current Password --}}
    <div class="mb-3">
        <label for="current_password" class="form-label">Current Password</label>
        <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
        @error('current_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- New Password --}}
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
         @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Confirm New Password --}}
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm New Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
    </div>

    <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection