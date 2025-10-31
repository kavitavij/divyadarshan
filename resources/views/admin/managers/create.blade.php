@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Add New Manager</h3>
            <a href="{{ route('admin.managers.index') }}" class="btn btn-sm btn-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.managers.store') }}" method="POST" enctype="multipart/form-data"
                x-data="{
                    role: '{{ old('role', 'temple_manager') }}',
                    photoPreview: null
                }">
                @csrf

                <div class="row">
                    {{-- Left Column: Credentials --}}
                    <div class="col-lg-8">
                        <h5 class="mb-3">Manager Credentials</h5>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                id="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    id="password_confirmation" required>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Photo and Assignment --}}
                    <div class="col-lg-4">
                        <h5 class="mb-3">Assignment & Photo</h5>
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <div class="text-center mb-2">
                                <img x-show="photoPreview" :src="photoPreview" alt="Photo Preview"
                                    class="img-thumbnail rounded-circle" width="150" height="150"
                                    style="object-fit: cover;">
                                <div x-show="!photoPreview" class="p-5 bg-light rounded-circle d-inline-block">
                                    <i class="fa-solid fa-user-tie fa-4x text-secondary"></i>
                                </div>
                            </div>
                            <input type="file" name="profile_photo" id="profile_photo"
                                class="form-control @error('profile_photo') is-invalid @enderror"
                                @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                            @error('profile_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" x-model="role" required>
                                <option value="temple_manager">Temple Manager</option>
                                <option value="hotel_manager">Hotel Manager</option>
                            </select>
                        </div>

                        <div class="mb-3" x-show="role === 'hotel_manager'" x-transition>
                            <label for="hotel_id" class="form-label">Assign to Hotel</label>
                            <select name="hotel_id" id="hotel_id"
                                class="form-select @error('hotel_id') is-invalid @enderror">
                                <option value="">Select a Hotel</option>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" @if (old('hotel_id') == $hotel->id) selected @endif>
                                        {{ $hotel->name }}</option>
                                @endforeach
                            </select>
                            @error('hotel_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" x-show="role === 'temple_manager'" x-transition>
                            <label for="temple_id" class="form-label">Assign to Temple</label>
                            <select name="temple_id" id="temple_id"
                                class="form-select @error('temple_id') is-invalid @enderror">
                                <option value="">Select a Temple</option>
                                @foreach ($temples as $temple)
                                    <option value="{{ $temple->id }}" @if (old('temple_id') == $temple->id) selected @endif>
                                        {{ $temple->name }}</option>
                                @endforeach
                            </select>
                            @error('temple_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4 border-top pt-3">
                    <a href="{{ route('admin.managers.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Manager</button>
                </div>
            </form>
        </div>
    </div>
@endsection
