@extends('layouts.app')

@push('styles')
    {{-- Stepper CSS (unchanged) --}}
    <style>
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem; /* More space below */
        }
        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }
        .stepper-item::before {
            position: absolute;
            content: "";
            border-bottom: 3px solid #e5e7eb; /* Gray line */
            width: 100%;
            top: 20px;
            left: -50%;
            z-index: 2;
        }
        .stepper-item::after {
            position: absolute;
            content: "";
            border-bottom: 3px solid #e5e7eb; /* Gray line */
            width: 100%;
            top: 20px;
            left: 50%;
            z-index: 2;
        }
        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 9999px; /* Circle */
            background: #ffffff;
            border: 3px solid #e5e7eb;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #6b7280; /* Gray text */
        }
        .stepper-item.active .step-counter {
            border-color: #4f46e5; /* Primary purple */
            background-color: #4f46e5;
            color: #ffffff;
        }
        .stepper-item.completed .step-counter {
            border-color: #16a34a; /* Green */
            background-color: #16a34a;
            color: #ffffff;
        }
        .stepper-item.completed::after {
            position: absolute;
            content: "";
            border-bottom: 3px solid #16a34a;
            width: 100%;
            top: 20px;
            left: 50%;
            z-index: 3;
        }
        .stepper-item:first-child::before { content: none; }
        .stepper-item:last-child::after { content: none; }
        .step-name {
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            color: #6b7280;
        }
        .stepper-item.active .step-name {
            font-weight: 700;
            color: #4f46e5;
        }
    </style>

    {{-- Slot Selection CSS (unchanged) --}}
    <style>
        .slot-list { display: flex; flex-direction: column; gap: 0.75rem; }
        .slot-item { display: flex; align-items: center; border: 2px solid #e5e7eb; border-radius: 8px; padding: 0.75rem 1rem; cursor: pointer; transition: all 0.2s ease-in-out; }
        .slot-item:hover { border-color: #6366f1; }
        .slot-item.selected { border-color: #4f46e5; background-color: #e0e7ff; }
        .slot-item input[type="radio"] { margin-right: 1rem; width: 1.25em; height: 1.25em; }
        .slot-time { font-weight: 600; color: #1f2937; flex-grow: 1; }
        .slot-item.selected .slot-time { color: #312e81; }
        .slot-availability { font-size: 0.9rem; font-weight: 500; padding: 0.2rem 0.6rem; border-radius: 9999px; background-color: #d1fae5; color: #065f46; }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-5">
    <div class="flex justify-center">
        <div class="w-full lg:w-10/12">
            <div class="bg-white shadow rounded-lg">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-bold">Book Your Darshan</h2>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="temple_id" class="block font-semibold mb-2">1. Select Temple</label>
                        <select name="temple_id" id="temple_id" class="form-control" required>
                            <option value="">-- Please choose a temple --</option>
                            @foreach ($temples as $temple)
                                <option value="{{ $temple->id }}" {{ isset($selectedTemple) && $selectedTemple->id == $temple->id ? 'selected' : '' }}>{{ $temple->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if (isset($selectedTemple))
                    <div class="stepper-wrapper">
                        <div class="stepper-item active">
                            <div class="step-counter">1</div>
                            <div class="step-name">Select Date & Time</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">2</div>
                            <div class="step-name">Add Devotee(s) Details</div>
                        </div>
                        <div class="stepper-item">
                            <div class="step-counter">3</div>
                            <div class="step-name">Review & Pay</div>
                        </div>
                    </div>

                    <form id="bookingForm" action="{{ route('booking.details') }}" method="GET">
                        <input type="hidden" name="temple_id" value="{{ $selectedTemple->id }}">
                        <div class="mb-4">
                            <label for="darshan_date" class="block font-semibold mb-2">2. Select Darshan Date</label>
                            <input type="date" id="darshan_date" name="selected_date" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">3. Select a Time Slot</label>
                            <div id="slots-loader" style="display: none;"><span>Loading slots...</span></div>
                            <div id="slots-container" class="mt-2">
                                <p class="text-gray-500">Please select a date to see available time slots.</p>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="number_of_people" class="block font-semibold mb-2">4. Number of People</label>
                            <input type="number" name="number_of_people" id="number_of_people" class="form-control" value="1" min="1" max="8" required>
                        </div>
                        <div class="mt-4">
                            <p id="totalCharge" class="text-blue-600 font-semibold"></p>
                        </div>
                        <div class="flex justify-center mt-6">
                           <button type="submit" class="animated-btn"><span>Next</span></button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- YOUR EXISTING SCRIPT IS UNCHANGED --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const templeSelect = document.getElementById('temple_id');

    // Redirect when temple is changed
    templeSelect.addEventListener('change', function () {
        if (this.value) {
            window.location.href = '/darshan-booking?temple_id=' + this.value;
        } else {
            window.location.href = '/darshan-booking';
        }
    });

    @if(isset($selectedTemple))
    const dateInput = document.getElementById('darshan_date');
    const slotsContainer = document.getElementById('slots-container');
    const slotsLoader = document.getElementById('slots-loader');
    const templeId = '{{ $selectedTemple->id }}';

    // Set minimum selectable date to today
    dateInput.setAttribute('min', new Date().toISOString().split('T')[0]);

    dateInput.addEventListener('change', function () {
        const selectedDate = this.value;
        if (!selectedDate) return;

        slotsLoader.style.display = 'block';
        slotsContainer.innerHTML = '';

        const url = '{{ route("api.temples.slots_for_date", ["temple" => $selectedTemple->id, "date" => "DATE_PLACEHOLDER"]) }}'.replace('DATE_PLACEHOLDER', encodeURIComponent(selectedDate));

        fetch(url)
            .then(res => res.json())
            .then(data => {
                slotsLoader.style.display = 'none';

                if (data.closed) {
                    slotsContainer.innerHTML = `<div class="alert alert-warning">${data.reason || 'Temple is closed on this date.'}</div>`;
                    return;
                }

                if (!data || data.length === 0) {
                    slotsContainer.innerHTML = '<p class="text-red-500">No slots available for this date.</p>';
                    return;
                }

                let html = '<div class="slot-list">';
                data.forEach(slot => {
                    html += `
                        <label class="slot-item" for="slot_${slot.id}">
                            <input type="radio" name="darshan_slot_id" id="slot_${slot.id}" value="${slot.id}" required>
                            <div class="slot-time">${slot.start_time} - ${slot.end_time}</div>
                            <div class="slot-availability">${slot.available} available</div>
                        </label>
                    `;
                });
                html += '</div>';
                slotsContainer.innerHTML = html;

                document.querySelectorAll('.slot-item').forEach(item => {
                    item.addEventListener('click', function() {
                        document.querySelectorAll('.slot-item').forEach(i => i.classList.remove('selected'));
                        this.classList.add('selected');
                        this.querySelector('input[type="radio"]').checked = true;
                    });
                });

            })
            .catch(err => {
                slotsLoader.style.display = 'none';
                slotsContainer.innerHTML = '<p class="text-red-500">Error fetching slots.</p>';
                console.error(err);
            });
    });
    @endif
});
</script>
@endpush
