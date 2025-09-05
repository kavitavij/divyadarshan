@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <h1>Edit Amenity</h1>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.amenities.update', $amenity->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.amenities._form')
                <button type="submit" class="btn btn-primary">Update Amenity</button>
            </form>
        </div>
    </div>
</div>
@endsection
