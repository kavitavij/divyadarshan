@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1>Manage Complaints</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Received On</th>
                            <th width="200px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>{{ $complaint->user->name ?? $complaint->name }}</td>
                                <td>{{ $complaint->subject }}</td>
                                <td>
                                    {{-- You can add status update logic here later --}}
                                    <span class="badge bg-warning">{{ $complaint->status }}</span>
                                </td>
                                <td>{{ $complaint->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <form action="{{ route('admin.complaints.destroy', $complaint->id) }}" method="POST"
                                        class="d-inline">
                                        <a href="{{ route('admin.complaints.show', $complaint->id) }}"
                                            class="btn btn-sm btn-info">View</a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this complaint?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No complaints found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
