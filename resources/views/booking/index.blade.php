@extends('layouts.app')

@push('styles')
    <style>
        .animated-btn {
            border-radius: 4px;
            background-color: #f4511e;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 18px;
            padding: 12px 20px;
            width: 180px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }

        .animated-btn span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .animated-btn span:after {
            content: '\00bb'; /* » arrow */
            position: absolute;
            opacity: 0;
            top: 0;
            right: -15px;
            transition: 0.5s;
        }

        .animated-btn:hover span {
            padding-right: 20px;
        }

        .animated-btn:hover span:after {
            opacity: 1;
            right: 0;
        }
    </style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-5">
    <div class="flex justify-center">
        <div class="w-full lg-w-10/12">
            <div class="bg-white shadow rounded-lg">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-bold">Book Your Darshan</h2>
                </div>
                <div class="p-6">
                    {{-- 1. Temple Selector --}}
                    <div class="mb-4">
                        <label for="temple_id" class="block font-semibold mb-2">1. Select Temple</label>
                        <select name="temple_id" id="temple_id" class="form-control" required>
                            <option value="">-- Please choose a temple --</option>
                            @foreach ($temples as $temple)
                                <option value="{{ $temple->id }}" {{ isset($selectedTemple) && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (isset($selectedTemple))
                    {{-- ###################  KEY CHANGES ARE HERE ################### --}}
                    <form id="bookingForm" action="{{ route('booking.details') }}" method="GET">
                        <input type="hidden" name="temple_id" value="{{ $selectedTemple->id }}">

                        {{-- 2. Date Picker (Simplified) --}}
                        <div class="mb-4">
                            <label for="darshan_date" class="block font-semibold mb-2">2. Select Darshan Date</label>
                            {{-- The name is changed to 'selected_date' to match your controller --}}
                            <input type="date" id="darshan_date" name="selected_date" class="form-control" required>
                        </div>

                        {{-- 3. Slots Container (Dynamically Filled) --}}
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">3. Select a Time Slot</label>
                            <div id="slots-loader" class="flex items-center space-x-2 text-blue-600" style="display: none;">
                                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Loading slots...</span>
                            </div>
                            <div id="slots-container" class="mt-2">
                                <p class="text-gray-500">Please select a date to see available time slots.</p>
                            </div>
                        </div>

                        {{-- 4. Number of People --}}
                        <div class="mb-4">
                            <label for="number_of_people" class="block font-semibold mb-2">4. Number of People</label>
                            <input type="number" name="number_of_people" id="number_of_people" class="form-control" value="1" min="1" required>
                        </div>

                        {{-- Darshan Charge & Total --}}
                        <div class="mt-4">
                             <p class="font-bold text-lg text-green-700">
                                 Darshan Charge: ₹{{ number_format($selectedTemple->darshan_charge ?? 0, 2) }} per person
                             </p>
                             <p id="totalCharge" class="text-blue-600 font-semibold"></p>
                        </div>

                        <div class="flex justify-center mt-6">
                           {{-- The button text is changed for clarity --}}
                           <button type="submit" class="animated-btn"><span>Next</span></button>
                        </div>
                    </form>
                    {{-- ################# END OF KEY CHANGES ##################### --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Temple selection reloads the page
    document.getElementById('temple_id').addEventListener('change', function () {
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

        // Set min date to today
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);

        // Helper function to convert time like "9:00 AM" to "11:00 AM"
        function formatSlotRange(startTime) {
            const [time, modifier] = startTime.split(' ');
            let [hours, minutes] = time.split(':').map(Number);

            // Convert 12 AM/PM to 0 or 12
            if (modifier === 'PM' && hours !== 12) hours += 12;
            if (modifier === 'AM' && hours === 12) hours = 0;

            // Add 2 hours
            let endHours = hours + 2;

            // Convert back to 12-hour format
            const endModifier = endHours >= 12 ? 'PM' : 'AM';
            if (endHours > 23) endHours = endHours % 24;

            let formattedEndHours = endHours % 12;
            if (formattedEndHours === 0) formattedEndHours = 12;

            const formattedEndTime = `${formattedEndHours}:${minutes.toString().padStart(2, '0')} ${endModifier}`;
            return `${startTime} - ${formattedEndTime}`;
        }

        dateInput.addEventListener('change', function () {
            const selectedDate = this.value;
            if (!selectedDate) {
                alert('Please select a valid date');
                return;
            }

            // Reset form elements visibility
            document.getElementById('number_of_people').closest('.mb-4').style.display = '';
            document.getElementById('totalCharge').closest('.mt-4').style.display = '';
            document.querySelector('#bookingForm .flex.justify-center').style.display = '';

            slotsLoader.style.display = 'block';
            slotsContainer.innerHTML = '';

            const url = `/api/temples/${templeId}/slots-for-date/${selectedDate}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    slotsLoader.style.display = 'none';

                    if (data.closed) {
                        slotsContainer.innerHTML = `
                            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
                                <strong class="font-bold">Notice:</strong>
                                <span class="block sm:inline">${data.reason || 'Temple is closed for bookings on this day.'}</span>
                                <span class="block text-sm mt-2 text-red-500 font-medium">Please choose another date.</span>
                            </div>
                        `;

                        // Hide form elements
                        document.getElementById('number_of_people').closest('.mb-4').style.display = 'none';
                        document.getElementById('totalCharge').closest('.mt-4').style.display = 'none';
                        document.querySelector('#bookingForm .flex.justify-center').style.display = 'none';
                        return;
                    }

                    // Render available slots
                    let slotsHtml = '';
                    data.forEach(slot => {
                        slotsHtml += `
                            <div class="flex items-center mb-2">
                                <input type="radio" name="darshan_slot_id" id="slot_${slot.id}" value="${slot.id}" class="mr-2" required>
                                <label for="slot_${slot.id}" class="text-sm font-medium text-gray-800">
                                    ${formatSlotRange(slot.time)}:
                                    <span class="ml-2 bg-green-200 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">${slot.available} available</span>
                                </label>
                            </div>
                        `;
                    });
                    slotsContainer.innerHTML = slotsHtml;
                })
                .catch(error => {
                    console.error('Error fetching slots:', error);
                    slotsLoader.style.display = 'none';
                    slotsContainer.innerHTML = '<p class="text-red-500">Error fetching slots.</p>';
                });
        });

        // Total charge calculation
        const numberInput = document.getElementById('number_of_people');
        const totalChargeEl = document.getElementById('totalCharge');
        const chargePerPerson = {{ $selectedTemple->darshan_charge ?? 0 }};

        function updateTotal() {
            const people = parseInt(numberInput.value) || 0;
            const total = people * chargePerPerson;
            totalChargeEl.textContent = people > 0 ? `Total Charge: ₹${total.toFixed(2)}` : '';
        }

        numberInput.addEventListener('input', updateTotal);
        updateTotal(); // Initial calculation
    @endif
});
</script>
@endpush

