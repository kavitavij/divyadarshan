@extends('layouts.admin')

@section('content')
    <h1>Add New Temple</h1>

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

    <form action="{{ route('admin.temples.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Temple Name"
                value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control" id="location" placeholder="Location"
                value="{{ old('location') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" style="height:150px" name="description" id="description" placeholder="Description">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="about" class="form-label">About Section</label>
            <textarea class="form-control" style="height:150px" name="about">{{ old('about') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="online_services" class="form-label">Online Services Section</label>
            <textarea class="form-control" style="height:150px" name="online_services">{{ old('online_services') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="darshan_charge" class="form-label">Darshan Charge (₹)</label>
            <input type="number" name="darshan_charge" class="form-control" id="darshan_charge"
                value="{{ old('darshan_charge', $temple->darshan_charge ?? 0) }}" min="0" step="1">

            <small class="text-muted">Set the price per darshan booking for this temple.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">News Items (up to 10)</label>
            <p class="form-text">Fill in the news items you want to display. Check the box to show it on the homepage news
                ticker.</p>
            @for ($i = 0; $i < 10; $i++)
                @php
                    // For the edit form, get existing news item, otherwise it's null
                    $newsItem = isset($temple) && isset($temple->news[$i]) ? $temple->news[$i] : null;
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
        <div class="mb-3">
            <label for="social_services" class="form-label">Social Services Section</label>
            <textarea class="form-control" style="height:150px" name="social_services">{{ old('social_services') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="file" name="image" class="form-control" id="image">
        </div>
        <div class="text-end">
            <a href="{{ route('admin.temples.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection
