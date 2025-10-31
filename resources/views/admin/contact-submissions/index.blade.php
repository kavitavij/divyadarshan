@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1>Contact Form Submissions</h1>
        <p>Messages sent by users through the public contact form.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Received On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->id }}</td>
                                <td>{{ $submission->name }}</td>
                                <td><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td>
                                <td>{{ Str::limit($submission->message, 100) }}</td>
                                <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                                <td>
                                    <form action="{{ route('admin.contact-submissions.destroy', $submission->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this message?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No messages have been received yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $submissions->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
