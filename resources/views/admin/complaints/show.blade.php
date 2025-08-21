@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1>Complaint Details</h1>

        <div class="card mt-4">
            <div class="card-header">
                <h4>Subject: {{ $complaint->subject }}</h4>
            </div>
            <div class="card-body">
                <p><strong>Received From:</strong> {{ $complaint->name }} ({{ $complaint->email }})</p>
                <p><strong>Status:</strong> <span class="badge bg-warning">{{ $complaint->status }}</span></p>
                <p><strong>Received On:</strong> {{ $complaint->created_at->format('d M Y, h:i A') }}</p>
                <hr>
                <h5>Full Message:</h5>
                <p style="white-space: pre-wrap;">{{ $complaint->message }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
