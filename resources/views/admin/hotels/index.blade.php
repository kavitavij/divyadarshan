@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Hotels</h1>
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">Add New Hotel</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Hotel Name</th>
                        <th>Location</th>
                        <th>Associated Temple</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($hotels as $hotel)
                    <tr>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->location }}</td>
                        <td>{{ $hotel->temple->name ?? 'N/A' }}</td>
                        <td>
                            <form action="{{ route('admin.hotels.destroy', $hotel->id) }}" method="POST">
                                <a href="{{ route('admin.hotels.edit', $hotel->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <a href="{{ route('admin.hotels.rooms.index', $hotel) }}" class="btn btn-sm btn-info">Manage Rooms</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No hotels have been added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
