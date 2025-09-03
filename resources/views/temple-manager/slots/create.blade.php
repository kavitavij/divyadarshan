@extends('layouts.temple-manager')



@section('title', 'Create New Darshan Slot')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Darshan Slot</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('temple-manager.slots.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="slot_date">Date</label>
                    <input type="date" name="slot_date" id="slot_date" class="form-control @error('slot_date') is-invalid @enderror" value="{{ old('slot_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('slot_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                         @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="total_capacity">Total Capacity (Number of People)</label>
                    <input type="number" name="total_capacity" id="total_capacity" class="form-control @error('total_capacity') is-invalid @enderror" value="{{ old('total_capacity') }}" min="1" required>
                    @error('total_capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Create Slot</button>
                <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
