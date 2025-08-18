@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Add New Hotel</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Hotel Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="location">Location / City</label>
                    <input type="text" name="location" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="temple_id">Associated Temple (Optional)</label>
                    <select name="temple_id" class="form-control">
                        <option value="">None</option>
                        @foreach($temples as $temple)
                            <option value="{{ $temple->id }}">{{ $temple->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="image">Hotel Image</label>
                    <input type="file" name="image" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Save Hotel</button>
            </form>
        </div>
    </div>
</div>
@endsection
