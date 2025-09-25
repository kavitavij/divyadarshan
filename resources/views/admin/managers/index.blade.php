@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Manage Managers</h1>
    <a href="{{ route('admin.managers.create') }}" class="btn btn-primary">Add New Manager</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Manages</th>
                        <th width="200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($managers as $manager)
                    <tr>
                        <td>{{ $manager->name }}</td>
                        <td>{{ $manager->email }}</td>
                        <td>
    @if ($manager->roles->isNotEmpty())
        <span class="badge bg-info">{{ Str::title(str_replace('_', ' ', $manager->roles->first()->name)) }}</span>
    @endif
</td>
                        <td>
                            @if ($manager->hotel)
                                {{ $manager->hotel->name }} (Hotel)
                            @elseif ($manager->temple)
                                {{ $manager->temple->name }} (Temple)
                            @else
                                <span class="text-muted">Not Assigned</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.managers.destroy', $manager->id) }}" method="POST">
                                <a class="btn btn-sm btn-info" href="{{ route('admin.managers.edit', $manager->id) }}">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this manager?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No managers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {!! $managers->links() !!}
        </div>
    </div>
</div>
@endsection