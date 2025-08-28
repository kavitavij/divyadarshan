@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Latest Updates</h1>
        <a href="{{ route('admin.latest_updates.create') }}" class="btn btn-primary">Add New Update</a>
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
                        <th>Message</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($updates as $update)
                        <tr>
                            <td>{{ $update->message }}</td>
                            <td>
                                <form action="{{ route('admin.latest_updates.destroy', $update->id) }}" method="POST">
                                    <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.latest_updates.edit', $update->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
