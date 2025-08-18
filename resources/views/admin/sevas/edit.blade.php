@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Edit Seva: {{ $seva->name }}</h1>
    <p>For temple: {{ $seva->temple->name }}</p>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.sevas.update', $seva) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label for="name">Seva Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $seva->name) }}" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $seva->description) }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="price">Price (₹)</label>
                        <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $seva->price) }}" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="type">Type</label>
                        <select name="type" class="form-control">
                            <option @if($seva->type == 'Daily') selected @endif>Daily</option>
                            <option @if($seva->type == 'Weekly') selected @endif>Weekly</option>
                            <option @if($seva->type == 'Special') selected @endif>Special</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update Seva</button>
                <a href="{{ route('admin.temples.sevas.index', $seva->temple_id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
