@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Darshan Slot</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('temple-manager.slots.update', $slot->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group mb-3">
                                <label>Date</label>
                                <input type="date" name="slot_date" class="form-control"
                                    value="{{ old('slot_date', $slot->slot_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Start Time</label>
                                <input type="time" name="start_time" class="form-control"
                                    value="{{ old('start_time', $slot->start_time) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>End Time</label>
                                <input type="time" name="end_time" class="form-control"
                                    value="{{ old('end_time', $slot->end_time) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Total Capacity (People)</label>
                                <input type="number" name="total_capacity" class="form-control"
                                    value="{{ old('total_capacity', $slot->total_capacity) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Slot</button>
                            <a href="{{ route('temple-manager.slots.index', ['date' => $slot->slot_date->format('Y-m-d')]) }}"
                                class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
