@extends('layouts.admin')
@section('content')
    <h1>Manage Ebooks</h1>
    <a href="{{ route('admin.ebooks.create') }}" class="btn btn-primary mb-3">Upload New Ebook</a>
    <table class="table table-bordered">
        {{-- ... table headers ... --}}
        <tbody>
            @foreach ($ebooks as $ebook)
            <tr>
                <td>
                    @if($ebook->cover_image_path)
                        {{-- THE FIX --}}
                        <img src="{{ Storage::url($ebook->cover_image_path) }}" height="50">
                    @endif
                </td>
                <td>{{ $ebook->title }}</td>
                <td>
                    {{-- THE FIX --}}
                    <a href="{{ Storage::url($ebook->ebook_file_path) }}" target="_blank">View PDF</a>
                    <a href="{{ route('admin.ebooks.edit', $ebook->id) }}" class="btn btn-sm btn-info">Edit</a>
                    {{-- ... delete form ... --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection