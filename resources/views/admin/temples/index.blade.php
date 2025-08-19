@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Manage Temples</h1>
        <a href="{{ route('admin.temples.create') }}" class="btn btn-primary">Add New Temple</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th width="500px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($temples as $temple)
                    <tr>
                        <td>{{ $temple->name }}</td>
                        <td>{{ $temple->location }}</td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('admin.temples.edit', $temple->id) }}">Edit Details / Daily Slots</a>
                            <a class="btn btn-warning btn-sm" href="{{ route('admin.temples.slots.index', $temple) }}">Manage Time Slots</a>
                            <a class="btn btn-secondary btn-sm" href="{{ route('admin.temples.sevas.index', $temple) }}">Manage Sevas</a>
                            <a class="btn btn-info btn-sm" href="{{ route('admin.temples.darshan_bookings', $temple) }}">Darshan Bookings</a>
                            <a class="btn btn-success btn-sm" href="{{ route('admin.temples.seva_bookings', $temple) }}">Seva Bookings</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
