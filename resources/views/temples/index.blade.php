@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Temples</h1>
        <a href="{{ route('admin.temples.create') }}" class="btn btn-primary">Add New Temple</a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($temples as $temple)
                    <tr>
                        <td>
                            @if($temple->image)
                                <img src="{{ asset($temple->image) }}" height="50" alt="{{ $temple->name }}">
                            @else
                                <span>No Image</span>
                            @endif
                        </td>
                        <td>{{ $temple->name }}</td>
                        <td>{{ $temple->location }}</td>
                        <td>
                            <form action="{{ route('admin.temples.destroy', $temple->id) }}" method="POST">
                                <a class="btn btn-sm btn-info" href="{{ route('admin.temples.edit', $temple->id) }}">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this temple?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {!! $temples->links() !!}
            </div>
        </div>
    </div>
@endsection