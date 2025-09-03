@extends('layouts.temple-manager')


@section('title', 'Add Daily Slots')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Add Daily Slots</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Select a Date and Enter Capacities</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('temple-manager.slots.bulk-store') }}" method="POST">
                @csrf

                <div class="form-group col-md-6 px-0">
                    <label for="slot_date"><strong>1. Select Date for Slots</strong></label>
                    <input type="date" name="slot_date" id="slot_date" class="form-control @error('slot_date') is-invalid @enderror" value="{{ old('slot_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('slot_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="form-group">
                    <label><strong>2. Enter Capacity for Default Slots</strong></label>
                    <p class="text-muted">Only slots with a capacity greater than 0 will be created. Leave blank or 0 to ignore.</p>

                    @foreach ($defaultSlots as $index => $slot)
                        <div class="form-row align-items-center mb-2">
                            <div class="col-md-4">
                                <label for="capacity_{{ $index }}" class="sr-only">{{ $slot['label'] }}</label>
                                <input type="text" readonly class="form-control-plaintext" value="{{ $slot['label'] }}">
                                <input type="hidden" name="slots[{{ $index }}][start_time]" value="{{ $slot['start_time'] }}">
                                <input type="hidden" name="slots[{{ $index }}][end_time]" value="{{ $slot['end_time'] }}">
                            </div>
                            <div class="col-md-4">
                                <label for="capacity_{{ $index }}" class="sr-only">Capacity</label>
                                <input type="number" name="slots[{{ $index }}][capacity]" id="capacity_{{ $index }}" class="form-control" value="1000">
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary mt-3">Create Slots</button>
                <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-secondary mt-3">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
