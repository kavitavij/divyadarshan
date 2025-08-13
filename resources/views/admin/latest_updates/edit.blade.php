@extends('layouts.admin')

@section('content')
    <h1>Edit Update</h1>

    <form action="{{ route('admin.latest_updates.update', $latestUpdate->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="message" class="form-label">Update Message</label>
            <input type="text" name="message" class="form-control" id="message" value="{{ $latestUpdate->message }}" required>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection