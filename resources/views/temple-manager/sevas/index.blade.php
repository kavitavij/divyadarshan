@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manage Sevas for {{ $temple->name }}</h1>
            <a href="{{ route('temple-manager.sevas.create') }}" class="btn btn-primary">Add New Seva</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sevas as $seva)
                            <tr>
                                <td>{{ $seva->name }}</td>
                                <td>{{ $seva->type }}</td>
                                <td>₹{{ number_format($seva->price, 2) }}</td>
                                <td>
                                    <form action="{{ route('temple-manager.sevas.destroy', $seva->id) }}" method="POST">
                                        <a href="{{ route('temple-manager.sevas.edit', $seva->id) }}"
                                            class="btn btn-sm btn-primary">Edit</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No sevas have been added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
