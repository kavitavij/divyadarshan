@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1>Add New Amenity</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf
                @include('admin.amenities._form')
                <button type="submit" class="btn btn-primary">Save Amenity</button>
            </form>
        </div>
    </div>
</div>
@endsection
