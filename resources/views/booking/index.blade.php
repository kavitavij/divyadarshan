@extends('layouts.app')

@push('styles')
    {{-- Add FullCalendar CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css">
    <style>
        /* --- Make the calendar container smaller --- */
        #calendar-wrapper {
            max-width: 400px !important; /* Forcing a smaller size to override other styles */
            margin: auto;
        }

        /* --- Reduce font sizes inside the calendar for a compact look --- */
        .fc .fc-toolbar-title {
            font-size: 1.1em; /* Reduced title font size */
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.85em;
            padding: 4px;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.8em;
        }


        /* Custom styles for the calendar days */
        .fc-day-available {
            background-color: #e8f5e9 !important; /* Light green */
            cursor: pointer;
        }
        .fc-day-full {
            background-color: #ffebee !important; /* Light red */
            cursor: default;
        }
        .fc-day-unavailable {
            background-color: #e3f2fd !important; /* Light blue */
            cursor: default;
        }
        .fc-daygrid-day:not(.fc-day-past):not(.fc-day-full):hover {
            background-color: #d1eaff !important; /* A slightly darker blue on hover */
        }
        /* Styles for the new legend */
        .calendar-legend {
            display: flex;
            flex-wrap: wrap; /* Allow legend to wrap on smaller screens */
            gap: 15px;
            margin-top: 5px;
            margin-bottom: 15px;
            font-size: 12px; /* Slightly smaller font */
            justify-content: center;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }
        .legend-available { background-color: #e8f5e9; }
        .legend-full { background-color: #ffebee; }
        .legend-unavailable { background-color: #e3f2fd; } /* Blue for the legend */

        /* Style for the status text inside calendar cells */
        .fc-day-status-text {
            font-size: 9px; /* Slightly smaller font */
            font-weight: 500;
            text-align: center;
            margin-top: 2px;
            display: block;
        }
        .fc-day-available .fc-day-status-text { color: #2e7d32; } /* Darker Green */
        .fc-day-full .fc-day-status-text { color: #c62828; } /* Darker Red */
        .fc-day-unavailable .fc-day-status-text { color: #1565c0; } /* Darker Blue */

    </style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h2>Book Your Darshan</h2></div>
                <div class="card-body">
                    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        {{-- 1. Select Temple --}}
                        <div class="form-group">
                            <label for="temple_id">1. Select Temple</label>
                            <select name="temple_id" id="temple_id" class="form-control" required>
                                <option value="">-- Please choose a temple --</option>
                                @foreach($temples as $temple)
                                    <option value="{{ $temple->id }}" data-slot-data='{!! json_encode($temple->slot_data ?? []) !!}'>{{ $temple->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Calendar for Date Selection --}}
                        <div class="form-group mt-4" id="calendar-container" style="display: none;">
                            <label>2. Select an Available Date</label>
                            {{-- Updated Legend --}}
                            <div class="calendar-legend">
                                <div class="legend-item"><span class="legend-color legend-available"></span> Available (Green)</div>
                                <div class="legend-item"><span class="legend-color legend-full"></span> Full (Red)</div>
                                <div class="legend-item"><span class="legend-color legend-unavailable"></span> Not Available (Blue)</div>
                            </div>
                            <div id="calendar-wrapper">
                                <div id='calendar'></div>
                            </div>
                        </div>

                        {{-- 3. Slot Selection --}}
                        <div class="form-group mt-4" id="slot-selector" style="display: none;">
                            <label>3. Select Available Slot</label>
                            <div id="slots-container" class="mt-2"></div>
                        </div>

                        {{-- 4. Number of People --}}
                        <div class="form-group mt-4" id="people-selector" style="display: none;">
                            <label for="number_of_people">4. Number of People</label>
                            <input type="number" name="number_of_people" id="number_of_people" class="form-control" min="1" max="5" required>
                        </div>
                        
                        <input type="hidden" name="darshan_slot_id" id="darshan_slot_id">

                        <button type="submit" class="btn btn-primary mt-4" id="submit-btn" style="display: none;">Confirm Booking</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load FullCalendar JS --}}
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get references to all the elements
    const templeSelect = document.getElementById('temple_id');
    const calendarContainer = document.getElementById('calendar-container');
    const calendarEl = document.getElementById('calendar');
    const slotSelector = document.getElementById('slot-selector');
    const slotsContainer = document.getElementById('slots-container');
    const peopleSelector = document.getElementById('people-selector');
    const submitBtn = document.getElementById('submit-btn');
    const darshanSlotIdInput = document.getElementById('darshan_slot_id');

    let calendar;
    let slotData = {};

    // Function to fetch and display time slots
    function fetchSlotsForDate(selectedDate) {
        const selectedTempleId = templeSelect.value;
        if (!selectedTempleId || !selectedDate) return;

        slotsContainer.innerHTML = '<p>Loading slots...</p>';
        slotSelector.style.display = 'block';

        fetch(`/api/temples/${selectedTempleId}/slots?date=${selectedDate}`)
            .then(response => response.json())
            .then(slots => {
                slotsContainer.innerHTML = '';
                if (slots.length > 0) {
                    slots.forEach(function(slot) {
                        const available = slot.total_capacity - slot.booked_capacity;
                        const isDisabled = available <= 0 ? 'disabled' : '';
                        const badgeClass = available > 0 ? 'bg-success' : 'bg-danger';
                        const slotHtml = `
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="slot_radio" id="slot_${slot.id}" value="${slot.id}" ${isDisabled}>
                                <label class="form-check-label" for="slot_${slot.id}">
                                    ${slot.start_time_formatted} - ${slot.end_time_formatted}
                                    <span class="badge ${badgeClass}">${available} spots left</span>
                                </label>
                            </div>`;
                        slotsContainer.insertAdjacentHTML('beforeend', slotHtml);
                    });
                } else {
                    slotsContainer.innerHTML = '<p class="text-danger">No time slots available for this day.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching slots:', error);
                slotsContainer.innerHTML = '<p class="text-danger">Could not load time slots.</p>';
            });
    }

    // Initialize the calendar
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto', // Let width and aspect ratio control the height
        aspectRatio: 1.5, // Make it wider than it is tall for a more compact look
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: '' // Removed month/week view toggle for a cleaner look
        },
        dayCellDidMount: function(info) {
            const dateStr = info.dateStr;
            let status = 'available';
            let statusText = 'Available'; // Default text

            if (slotData[dateStr] === 'full') {
                status = 'full';
                statusText = 'Full';
            }
            if (info.isPast) {
                status = 'unavailable';
                statusText = 'Not Avail.';
            }
            
            info.el.classList.remove('fc-day-available', 'fc-day-full', 'fc-day-unavailable');
            info.el.classList.add('fc-day-' + status);

            // Add status text inside the cell
            const dayGridContent = info.el.querySelector('.fc-daygrid-day-frame');
            if (dayGridContent) {
                 // Check if text already exists to prevent duplicates on re-render
                if (dayGridContent.querySelector('.fc-day-status-text')) {
                    dayGridContent.querySelector('.fc-day-status-text').innerText = statusText;
                    return;
                };

                const statusEl = document.createElement('div');
                statusEl.classList.add('fc-day-status-text');
                statusEl.innerText = statusText;
                dayGridContent.appendChild(statusEl);
            }
        },
        dateClick: function(info) {
            // Prevent clicking on unavailable dates
            if (info.dayEl.classList.contains('fc-day-unavailable') || info.dayEl.classList.contains('fc-day-full')) {
                return;
            }
            // A valid date was clicked, fetch the slots
            fetchSlotsForDate(info.dateStr);
        }
    });
    calendar.render();

    // Listen for changes on the Temple dropdown
    templeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            try {
                slotData = JSON.parse(selectedOption.getAttribute('data-slot-data'));
                calendarContainer.style.display = 'block';
                calendar.render(); // Re-render to apply new colors and text
                slotSelector.style.display = 'none';
                peopleSelector.style.display = 'none';
                submitBtn.style.display = 'none';
            } catch (e) {
                console.error("Failed to parse slot data:", e);
                calendarContainer.style.display = 'none';
            }
        } else {
            calendarContainer.style.display = 'none';
        }
    });

    // Listen for a slot selection
    slotsContainer.addEventListener('change', function(e) {
        if (e.target.name === 'slot_radio' && e.target.checked) {
            darshanSlotIdInput.value = e.target.value;
            peopleSelector.style.display = 'block';
            submitBtn.style.display = 'block';
        }
    });
});
</script>
@endpush
