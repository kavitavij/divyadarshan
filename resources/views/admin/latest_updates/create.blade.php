@extends('layouts.admin')

@section('content')
    <h1>Add New Update</h1>

    <form action="{{ route('admin.latest_updates.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="message" class="form-label">Update Message</label>
            <input type="text" name="message" class="form-control" id="message" placeholder="Enter the update text" required>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.latest_updates.index') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-primary">Save Update</button>
        </div>
    </form>
@endsection