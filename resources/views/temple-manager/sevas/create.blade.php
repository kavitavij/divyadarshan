@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1>Add New Seva to {{ $temple->name }}</h1>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('temple-manager.sevas.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Seva Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label>Price (₹)</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label>Type</label>
                            <select name="type" class="form-control">
                                <option>Daily</option>
                                <option>Weekly</option>
                                <option>Special</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Seva</button>
                    <a href="{{ route('temple-manager.sevas.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
