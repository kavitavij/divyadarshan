@extends('layouts.app')

@push('styles')
    <style>
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .stepper-item::before,
        .stepper-item::after {
            position: absolute;
            content: "";
            border-bottom: 3px solid #e5e7eb;
            width: 100%;
            top: 20px;
            z-index: 2;
        }

        .dark .stepper-item::before,
        .dark .stepper-item::after {
            border-bottom-color: #374151;
            /* dark mode line color */
        }

        .stepper-item::before {
            left: -50%;
        }

        .stepper-item::after {
            left: 50%;
        }

        .stepper-item .step-counter {
            position: relative;
            z-index: 5;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            border-radius: 9999px;
            background: #ffffff;
            border: 3px solid #e5e7eb;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #6b7280;
        }

        .dark .stepper-item .step-counter {
            background: #1f2937;
            border-color: #374151;
            color: #9ca3af;
        }

        .stepper-item.active .step-counter {
            border-color: #4f46e5;
            background-color: #4f46e5;
            color: #ffffff;
        }

        .stepper-item.completed .step-counter {
            border-color: #16a34a;
            background-color: #16a34a;
            color: #ffffff;
        }

        .stepper-item.completed::after {
            border-bottom-color: #16a34a;
            z-index: 3;
        }

        .stepper-item:first-child::before {
            content: none;
        }

        .stepper-item:last-child::after {
            content: none;
        }

        .step-name {
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
            color: #6b7280;
        }

        .dark .step-name {
            color: #9ca3af;
        }

        .stepper-item.active .step-name {
            font-weight: 700;
            color: #4f46e5;
        }

        .slot-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .slot-item {
            display: flex;
            align-items: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            background-color: #fff;
        }

        .dark .slot-item {
            border-color: #374151;
            background-color: #1f2937;
        }

        .slot-item:hover {
            border-color: #6366f1;
        }

        .slot-item.selected {
            border-color: #4f46e5;
            background-color: #e0e7ff;
        }

        .dark .slot-item.selected {
            background-color: #312e81;
        }

        .slot-item input[type="radio"] {
            margin-right: 1rem;
            width: 1.25em;
            height: 1.25em;
        }

        .slot-time {
            font-weight: 600;
            color: #1f2937;
            flex-grow: 1;
        }

        .dark .slot-time {
            color: #f9fafb;
        }

        .slot-item.selected .slot-time {
            color: #312e81;
        }

        .dark .slot-item.selected .slot-time {
            color: #c7d2fe;
        }

        .slot-availability {
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            background-color: #d1fae5;
            color: #065f46;
        }

        .dark .slot-availability {
            background-color: #064e3b;
            color: #bbf7d0;
        }

        /* Media Query for smaller screens */
        @media (max-width: 640px) {
            .step-name {
                font-size: 0.75rem;
            }

            .stepper-item .step-counter {
                width: 35px;
                height: 35px;
            }

            .stepper-item::before,
            .stepper-item::after {
                top: 17px;
            }
        }

        .animated-btn {
            background: #4f46e5;
            color: #fff;
            padding: 0.75rem 2.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.08);
        }

        .animated-btn:hover {
            background: #3730a3;
        }

        .dark .animated-btn {
            background: #6366f1;
            color: #fff;
        }

        .dark .animated-btn:hover {
            background: #3730a3;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-5">
        <div class="flex justify-center">
            <div class="w-full lg:w-10/12">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="border-b px-6 py-4 border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Book Your Darshan</h2>
                    </div>
                    <div class="p-6">
                        <div class="mb-4">
                            <label for="temple_id" class="block font-semibold mb-2 text-gray-800 dark:text-gray-200">1.
                                Select Temple</label>
                            <select name="temple_id" id="temple_id" class="form-control dark:bg-gray-700 dark:text-gray-200"
                                required>
                                <option value="">-- Please choose a temple --</option>
                                @foreach ($temples as $temple)
                                    <option value="{{ $temple->id }}"
                                        {{ isset($selectedTemple) && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                        {{ $temple->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (isset($selectedTemple))
                            {{-- Stepper --}}
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

                            {{-- Form --}}
                            <form id="bookingForm" action="{{ route('booking.details') }}" method="GET">
                                <input type="hidden" name="temple_id" value="{{ $selectedTemple->id }}">

                                <div class="mb-4">
                                    <label for="darshan_date"
                                        class="block font-semibold mb-2 text-gray-800 dark:text-gray-200">2. Select Darshan
                                        Date</label>
                                    <input type="date" id="darshan_date" name="selected_date"
                                        class="form-control dark:bg-gray-700 dark:text-gray-200" required>
                                </div>

                                <div class="mb-4">
                                    <label class="block font-semibold mb-2 text-gray-800 dark:text-gray-200">3. Select a
                                        Time
                                        Slot</label>
                                    <div id="slots-loader" style="display: none;"><span
                                            class="text-gray-800 dark:text-gray-300">Loading slots...</span></div>
                                    <div id="slots-container" class="mt-2 text-gray-600 dark:text-gray-400">
                                        <p>Please select a date to see available time slots.</p>
                                    </div>
                                </div>
                                <div class="mb-4 block">
                                    <label for="number_of_people"
                                        class="block font-semibold mb-2 text-gray-800 dark:text-gray-200">4. Number of
                                        People :
                                        <input type="number" name="number_of_people"
                                            style="background-color: white; color: black; border: 1px solid #ccc; padding: 0.5rem; border-radius: 0.25rem;"
                                            value="1" min="1" max="8" required>
                                    </label>
                                </div>
                                <div class="mt-4">
                                    <p id="totalCharge" class="text-blue-600 dark:text-blue-400 font-semibold"></p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Helper function to format a Date as YYYY-MM-DD in local time
            // This avoids timezone bugs from .toISOString()
            function toLocalISOString(date) {
                const year = date.getFullYear();
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            const templeSelect = document.getElementById('temple_id');

            // Redirect when temple is changed
            templeSelect.addEventListener('change', function() {
                if (this.value) {
                    window.location.href = '/darshan-booking?temple_id=' + this.value;
                } else {
                    window.location.href = '/darshan-booking';
                }
            });

            @if (isset($selectedTemple))
                const dateInput = document.getElementById('darshan_date');
                const slotsContainer = document.getElementById('slots-container');
                const slotsLoader = document.getElementById('slots-loader');
                const templeId = '{{ $selectedTemple->id }}';

                // --- START OF FIX ---

                // Set minimum and maximum selectable dates
                const today = new Date();
                const maxDate = new Date();
                maxDate.setMonth(today.getMonth() + 5); // Add 5 months

                dateInput.setAttribute('min', toLocalISOString(today));
                dateInput.setAttribute('max', toLocalISOString(maxDate));

                // --- END OF FIX ---


                dateInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    if (!selectedDate) return;

                    slotsLoader.style.display = 'block';
                    slotsContainer.innerHTML = '';

                    const url =
                        '{{ route('api.temples.slots_for_date', ['temple' => $selectedTemple->id, 'date' => 'DATE_PLACEHOLDER']) }}'
                        .replace('DATE_PLACEHOLDER', encodeURIComponent(selectedDate));

                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            slotsLoader.style.display = 'none';

                            if (data.closed) {
                                slotsContainer.innerHTML =
                                    `<div class="alert alert-warning">${data.reason || 'Temple is closed on this date.'}</div>`;
                                return;
                            }

                            if (!data || data.length === 0) {
                                slotsContainer.innerHTML =
                                    '<p class="text-red-500">No slots available for this date.</p>';
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
                                    document.querySelectorAll('.slot-item').forEach(i =>
                                        i.classList.remove('selected'));
                                    this.classList.add('selected');
                                    this.querySelector('input[type="radio"]').checked =
                                        true;
                                });
                            });

                        })
                        .catch(err => {
                            slotsLoader.style.display = 'none';
                            slotsContainer.innerHTML =
                                '<p class="text-red-500">Error fetching slots.</p>';
                            console.error(err);
                        });
                });
            @endif
        });
    </script>
@endpush
