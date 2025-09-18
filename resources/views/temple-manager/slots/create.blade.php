@extends('layouts.temple-manager')



@section('title', 'Create New Darshan Slot')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Create New Darshan Slot</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('temple-manager.slots.store') }}" method="POST" id="multi-slot-form">
                @csrf


                <div class="form-group">
                    <label for="slot_date">Date</label>
                    <input type="date" name="slot_date" id="slot_date" class="form-control @error('slot_date') is-invalid @enderror" value="{{ old('slot_date') }}" min="{{ date('Y-m-d') }}" required>
                    @error('slot_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="slots-container">
                    <div class="slot-row row align-items-end mb-3">
                        <div class="col-md-3">
                            <label>Start Time</label>
                            <input type="time" name="slots[0][start_time]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>End Time</label>
                            <input type="time" name="slots[0][end_time]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Capacity</label>
                            <input type="number" name="slots[0][capacity]" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-remove-slot d-none">Remove</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary mb-3" id="add-slot-btn">Add Another Slot</button>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                let slotIndex = 1;
                const slotsContainer = document.getElementById('slots-container');
                document.getElementById('add-slot-btn').addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'slot-row row align-items-end mb-3';
                    row.innerHTML = `
                        <div class="col-md-3">
                            <label>Start Time</label>
                            <input type="time" name="slots[${slotIndex}][start_time]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label>End Time</label>
                            <input type="time" name="slots[${slotIndex}][end_time]" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label>Capacity</label>
                            <input type="number" name="slots[${slotIndex}][capacity]" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-remove-slot">Remove</button>
                        </div>
                    `;
                    slotsContainer.appendChild(row);
                    slotIndex++;
                    updateRemoveButtons();
                });
                function updateRemoveButtons() {
                    document.querySelectorAll('.btn-remove-slot').forEach(btn => {
                        btn.classList.remove('d-none');
                        btn.onclick = function() {
                            btn.closest('.slot-row').remove();
                            updateRemoveButtons();
                        };
                    });
                    // Only hide remove button if only one slot left
                    if (document.querySelectorAll('.slot-row').length === 1) {
                        document.querySelector('.btn-remove-slot').classList.add('d-none');
                    }
                }
                updateRemoveButtons();
            });
            </script>

                <button type="submit" class="btn btn-primary">Create Slot</button>
                <a href="{{ route('temple-manager.slots.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
