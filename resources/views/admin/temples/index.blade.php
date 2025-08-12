@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between">
        <h1>Manage Temples</h1>
        <a href="{{ route('admin.temples.create') }}" class="btn btn-primary">Add New Temple</a>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-2">
            {{ $message }}
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($temples as $temple)
        <tr>
            <td>{{ $temple->name }}</td>
            <td>{{ $temple->location }}</td>
            <td>
                <form action="{{ route('admin.temples.destroy', $temple->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('admin.temples.edit', $temple->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
@endsection