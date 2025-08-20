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
        <div class="card">
            <div class="card-header"><h3 class="card-title">Basic Details</h3></div>
            <div class="card-body">
                <div class="mb-3"><label for="name" class="form-label">Name</label><input type="text" name="name" class="form-control" id="name" value="{{ $temple->name }}"></div>
                <div class="mb-3"><label for="location" class="form-label">Location</label><input type="text" name="location" class="form-control" id="location" value="{{ $temple->location }}"></div>
                <div class="mb-3"><label for="description" class="form-label">Short Description</label><textarea class="form-control" style="height:100px" name="description" id="description">{{ $temple->description }}</textarea></div>
            </div>
        </div>
        
        <hr class="my-4">
        
        {{-- Detailed Content Sections with HTML/CSS Editor --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Page Content (with HTML/CSS Support)</h3></div>
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

        {{-- News Editor --}}
        <div class="mb-3">
            <label class="form-label">News Items (up to 10)</label>
            <p class="form-text">Fill in news items. Check the box to show it on the homepage news ticker.</p>
            @for ($i = 0; $i < 10; $i++)
                @php
                    $newsItem = $temple->news[$i] ?? null;
                @endphp
                <div class="input-group mb-2">
                    <span class="input-group-text">{{ $i + 1 }}</span>
                    <input type="text" name="news_items[{{ $i }}]" class="form-control" placeholder="News item #{{ $i + 1 }}" value="{{ old('news_items.'.$i, $newsItem['text'] ?? '') }}">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="news_tickers[]" value="{{ $i }}" {{ old('news_tickers.'.$i, $newsItem['show_on_ticker'] ?? false) ? 'checked' : '' }} title="Show on ticker">
                    </div>
                </div>
            @endfor
        </div>

        <hr class="my-4">

        {{-- Slot Booking Calendar --}}
        <div class="mb-3">
            <h3 class="form-label">Manage Darshan Slot Availability</h3>
            <p class="form-text">Set the status for each day. Days left as "Not Available" will not be saved.</p>
            <div class="row">
                @foreach($adminCalendars as $calendar)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card">
                            <div class="card-header text-center"><strong>{{ $calendar['month_name'] }}</strong></div>
                            <div class="card-body">
                                @foreach($calendar['days'] as $day)
                                    <div class="mb-2 d-flex align-items-center">
                                        <label for="slot_{{ $day['date'] }}" class="form-label me-2" style="width: 50px;">Day {{ $day['day_number'] }}</label>
                                        <select name="slot_data[{{ $day['date'] }}]" id="slot_{{ $day['date'] }}" class="form-select form-select-sm">
                                            <option value="not_available" @if($day['status'] == 'not_available') selected @endif>Not Available</option>
                                            <option value="available" @if($day['status'] == 'available') selected @endif>Available</option>
                                            <option value="full" @if($day['status'] == 'full') selected @endif>Full</option>
                                        </select>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <hr class="my-4">

        {{-- Image Upload --}}
        <div class="mb-3">
            <label for="image" class="form-label">Current Image</label>
            <div>@if($temple->image)<img src="{{ asset($temple->image) }}" height="100" class="mb-2" alt="{{ $temple->name }}">@endif</div>
            <label for="image" class="form-label">Upload New Image (optional)</label>
            <input type="file" name="image" class="form-control" id="image">
        </div>

        {{-- Action Buttons --}}
        <div class="text-end">
            <a href="{{ route('admin.temples.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection

@push('scripts')
    {{-- Load TinyMCE from its CDN --}}
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
      // Initialize TinyMCE on all textareas with the 'wysiwyg-editor' class
      tinymce.init({
        selector: 'textarea.wysiwyg-editor',
        plugins: 'code table lists image link media preview fullscreen',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image link media | preview fullscreen',
        height: 400,
        // These settings allow any HTML and CSS, including <style> tags
        extended_valid_elements: '+*[*]',
        valid_elements: '+*[*]',
        valid_children: '+body[style]',
        verify_html: false,
      });
    </script>
@endpush
