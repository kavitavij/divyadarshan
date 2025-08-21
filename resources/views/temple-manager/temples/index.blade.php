@extends('layouts.temple-manager')

@section('title', 'Manage Temples')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Manage Temples</h2>
            <a href="{{ route('temple-manager.temples.create') }}" class="btn btn-primary">➕ Add Temple</a>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Temples Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($temples as $temple)
                            <tr>
                                <td>{{ $temple->id }}</td>
                                <td>{{ $temple->name }}</td>
                                <td>{{ $temple->location }}</td>
                                <td>{{ Str::limit($temple->description, 50) }}</td>
                                <td>{{ $temple->created_at->format('d M, Y') }}</td>
                                <td>
                                    <a href="{{ route('temple-manager.temples.edit', $temple->id) }}"
                                        class="btn btn-sm btn-warning">✏ Edit</a>
                                    <form action="{{ route('temple-manager.temples.destroy', $temple->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this temple?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">🗑 Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No temples found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $temples->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
