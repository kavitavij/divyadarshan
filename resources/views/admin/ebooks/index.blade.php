@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Ebooks</h1>
        <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary">Upload New Ebook</a>
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
                        <th>Cover</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th width="280px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ebooks as $ebook)
                    <tr>
                        <td>
                            @if($ebook->cover_image_path)
                                <img src="{{ Storage::url($ebook->cover_image_path) }}" height="50" alt="{{ $ebook->title }}">
                            @endif
                        </td>
                        <td>{{ $ebook->title }}</td>
                        <td>{{ $ebook->author }}</td>
                        <td>
                            {{-- THE FIX IS HERE --}}
                            <form action="{{ route('admin.ebooks.destroy', $ebook->id) }}" method="POST">
                                <a class="btn btn-sm btn-secondary" href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank">View</a>
                                <a class="btn btn-sm btn-info" href="{{ route('admin.ebooks.edit', $ebook->id) }}">Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this ebook?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {!! $ebooks->links() !!}
            </div>
        </div>
    </div>
@endsection