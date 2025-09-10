@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1>Edit Temple: {{ $temple->name }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.temples.update', $temple->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Basic Details --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Basic Details</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            value="{{ old('name', $temple->name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" id="location"
                            value="{{ old('location', $temple->location) }}">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Short Description</label>
                        <textarea class="form-control" style="height:100px" name="description" id="description">{{ old('description', $temple->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="terms_and_conditions" class="form-label">Darshan Booking Terms & Conditions</label>
                        <textarea class="form-control" id="terms_and_conditions" name="terms_and_conditions" rows="10" placeholder="Enter booking rules, dress code, etc. You can use HTML for formatting (e.g., <strong>, <ul>, <li>).">{{ old('terms_and_conditions', $temple->terms_and_conditions) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="darshan_charge" class="form-label">Darshan Charge (₹)</label>
                        <input type="number" name="darshan_charge" class="form-control" id="darshan_charge"
                            value="{{ old('darshan_charge', $temple->darshan_charge ?? 0) }}" min="0" step="1">
                        <small class="text-muted">Set the price per darshan booking for this temple.</small>
                    </div>
                </div>
            </div>

             {{-- Offered Services --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Offered Services</h3>
                </div>
                <div class="card-body">
                    @php
                        $services = [
                            'darshan' => 'Darshan Booking',
                            'seva' => 'Seva Offerings',
                            'accommodation' => 'Accommodation',
                            'donation' => 'Donations',
                            'ebooks' => 'Spiritual E-Books',
                        ];
                        $offered = $temple->offered_services ?? [];
                    @endphp

                    @foreach ($services as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="offered_services[]"
                                value="{{ $key }}" id="{{ $key }}"
                                {{ in_array($key, $offered ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ✅ NEW: Offered Social Services --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Offered Social Services</h3>
                </div>
                <div class="card-body">
                    @php
                        $social_services_options = [
                            'annadaan' => 'Annadaan (Food Donation)',
                            'health_camps' => 'Health Camps',
                            'education_aid' => 'Education Aid',
                            'environment_care' => 'Environment Care',
                            'community_seva' => 'Community Seva',
                        ];
                        $offered_social = $temple->offered_social_services ?? [];
                    @endphp

                    @foreach ($social_services_options as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="offered_social_services[]"
                                value="{{ $key }}" id="{{ $key }}"
                                {{ in_array($key, $offered_social) ? 'checked' : '' }}>
                            <label class="form-check-label" for="{{ $key }}">
                                {{ $label }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Page Content --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Page Content </h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="about" class="form-label">About Section</label>
                        <textarea class="form-control wysiwyg-editor" name="about">{{ old('about', $temple->about) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="online_services" class="form-label">Online Services Section</label>
                        <textarea class="form-control wysiwyg-editor" name="online_services">{{ old('online_services', $temple->online_services) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="social_services" class="form-label">Social Services Section</label>
                        <textarea class="form-control wysiwyg-editor" name="social_services">{{ old('social_services', $temple->social_services) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- News Items --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">News Items (up to 10)</h3>
                </div>
                <div class="card-body">
                    <p class="form-text mb-3">Fill in news items. Check the box to show it on the homepage news ticker.</p>
                    @for ($i = 0; $i < 10; $i++)
                        @php
                            $newsItem = $temple->news[$i] ?? null;
                        @endphp
                        <div class="input-group mb-2">
                            <span class="input-group-text">{{ $i + 1 }}</span>
                            <input type="text" name="news_items[{{ $i }}]" class="form-control"
                                placeholder="News item #{{ $i + 1 }}"
                                value="{{ old('news_items.' . $i, $newsItem['text'] ?? '') }}">
                            <div class="input-group-text">
                                <input class="form-check-input mt-0" type="checkbox" name="news_tickers[]"
                                    value="{{ $i }}"
                                    {{ old('news_tickers.' . $i, $newsItem['show_on_ticker'] ?? false) ? 'checked' : '' }}
                                    title="Show on ticker">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Slot Booking --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Manage Darshan Slot Availability</h3>
                </div>
                <div class="card-body">
                    <p class="form-text mb-3">Set the status for each day. Days left as "Not Available" will not be saved.
                    </p>
                    <div class="row">
                        @foreach ($adminCalendars as $calendar)
                            <div class="col-md-6 col-lg-3 mb-4">
                                <div class="card">
                                    <div class="card-header text-center"><strong>{{ $calendar['month_name'] }}</strong>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($calendar['days'] as $day)
                                            <div class="mb-2 d-flex align-items-center">
                                                <label for="slot_{{ $day['date'] }}" class="form-label me-2"
                                                    style="width: 50px;">Day {{ $day['day_number'] }}</label>
                                                <select name="slot_data[{{ $day['date'] }}]"
                                                    id="slot_{{ $day['date'] }}" class="form-select form-select-sm">
                                                    <option value="not_available"
                                                        @if ($day['status'] == 'not_available') selected @endif>Not Available
                                                    </option>
                                                    <option value="available"
                                                        @if ($day['status'] == 'available') selected @endif>Available</option>
                                                    <option value="full"
                                                        @if ($day['status'] == 'full') selected @endif>Full</option>
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="mb-4">
                <label for="image" class="form-label">Current Image</label>
                <div class="mb-2">
                    @if ($temple->image)
                        <img src="{{ asset($temple->image) }}" height="100" alt="{{ $temple->name }}">
                    @endif
                </div>
                <label for="image" class="form-label">Upload New Image (optional)</label>
                <input type="file" name="image" class="form-control" id="image">
            </div>

            {{-- Action Buttons --}}
            <div class="text-end">
                <a href="{{ route('admin.temples.index') }}" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/o5wfjvocpzdett1nnvnmeopwgl8i2gp5j1smdegnaukyamkf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.wysiwyg-editor',
            plugins: 'code table lists image link media preview fullscreen',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link media | preview fullscreen',
            height: 400,
            extended_valid_elements: '+*[*]',
            valid_elements: '+*[*]',
            valid_children: '+body[style]',
            verify_html: false,
        });
    </script>
@endpush
