@extends('layouts.hotel-manager') {{-- Assuming this is your hotel manager layout --}}

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
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{-- Form points to the hotel-manager.profile.update route --}}
                        <form action="{{ route('hotel-manager.profile.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- Profile Photo Display & Upload --}}
                            <div class="mb-3 text-center position-relative">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                    class="img-thumbnail rounded-circle" width="150" height="150"
                                    style="object-fit: cover;">
                                @if ($user->profile_photo_path)
                                    <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute"
                                        style="top: -10px; right: calc(50% - 85px); width: 24px; height: 24px; padding: 0; line-height: 24px;"
                                        onclick="removeProfilePhoto()">
                                        <i class="fas fa-times" style="font-size: 12px;"></i>
                                    </button>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="profile_photo" class="form-label">Update Profile Photo</label>
                                <input type="file" name="profile_photo" id="profile_photo"
                                    class="form-control @error('profile_photo') is-invalid @enderror">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" disabled>
                                <div class="form-text">Your email address cannot be changed. Please contact the
                                    administrator to update it.</div>
                            </div>

                            <hr class="my-4">
                            <p class="text-muted">To change your password, please fill out all three fields below.
                                Otherwise, leave them blank.</p>

                            {{-- Current Password --}}
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- New Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control">
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

@push('scripts')
    <script>
        function removeProfilePhoto() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/hotel-manager/profile-photo';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
