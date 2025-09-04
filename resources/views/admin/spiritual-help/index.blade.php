@extends('layouts.admin')
@section('title', 'Spiritual Help Requests')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Spiritual Help Requests</h1>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Query Type</th>
                            <th>Temple</th>
                            <th>Message</th>
                            <th>Submitted At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td>{{ $request->name }} <br><small class="text-muted">{{ $request->city }}</small></td>
                            <td>{{ $request->contact_info }} <br><small class="text-muted">{{ $request->preferred_time }}</small></td>
                            <td>{{ $request->query_type }}</td>
                            <td>{{ $request->temple->name ?? 'N/A' }}</td>
                            <td style="max-width: 300px;">{{ $request->message }}</td>
                            <td>{{ $request->created_at->format('d M, Y h:i A') }}</td>
                            <td>
                                <form action="{{ route('admin.spiritual-help.destroy', $request) }}" method="POST" onsubmit="return confirm('Mark as resolved and delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No new requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $requests->links() }}</div>
        </div>
    </div>
</div>
@endsection
