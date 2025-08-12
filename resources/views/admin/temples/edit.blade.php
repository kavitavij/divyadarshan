@extends('layouts.admin')

@section('content')
    <h1>Edit Temple</h1>

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
        <div class="mb-3"><label for="name" class="form-label">Name</label><input type="text" name="name" class="form-control" id="name" value="{{ $temple->name }}"></div>
        <div class="mb-3"><label for="location" class="form-label">Location</label><input type="text" name="location" class="form-control" id="location" value="{{ $temple->location }}"></div>
        <div class="mb-3"><label for="description" class="form-label">Description</label><textarea class="form-control" style="height:150px" name="description" id="description">{{ $temple->description }}</textarea></div>
        
        <hr class="my-4">
        
        {{-- Detailed Sections --}}
        <div class="mb-3"><label for="about" class="form-label">About Section</label><textarea class="form-control" style="height:150px" name="about">{{ $temple->about }}</textarea></div>
        <div class="mb-3"><label for="online_services" class="form-label">Online Services Section</label><textarea class="form-control" style="height:150px" name="online_services">{{ $temple->online_services }}</textarea></div>
        <div class="mb-3"><label for="social_services" class="form-label">Social Services Section</label><textarea class="form-control" style="height:150px" name="social_services">{{ $temple->social_services }}</textarea></div>

        <hr class="my-4">

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