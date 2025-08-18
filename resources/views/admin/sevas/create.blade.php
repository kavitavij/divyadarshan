@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Add New Seva to {{ $temple->name }}</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.temples.sevas.store', $temple) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="name">Seva Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label for="price">Price (₹)</label>
                        <input type="number" name="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="type">Type</label>
                        <select name="type" class="form-control">
                            <option>Daily</option>
                            <option>Weekly</option>
                            <option>Special</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Seva</button>
            </form>
        </div>
    </div>
</div>
@endsection
