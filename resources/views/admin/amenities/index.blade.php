@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Amenities</h1>
        <a href="{{ route('admin.amenities.create') }}" class="btn btn-primary">Add New Amenity</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amenities as $amenity)
                    <tr>
                        <td><i class="{{ $amenity->icon }} fa-fw"></i></td>
                        <td>{{ $amenity->name }}</td>
                        <td>
                             <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" method="POST">
                                <a href="{{ route('admin.amenities.edit', $amenity->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
