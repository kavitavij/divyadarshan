@extends('layouts.app')

@push('styles')
    {{-- Custom styles are no longer needed as we are using Tailwind classes directly --}}
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h2>{{ isset($selectedTemple) && $selectedTemple ? 'Book Your Darshan for ' . $selectedTemple->name : 'Book Your Darshan' }}</h2>
                </div>
                <div class="card-body">
                    {{-- 1. Temple Selector --}}
                    <div class="form-group">
                        <label for="temple_id">1. Select Temple</label>
                        <select name="temple_id" id="temple_id" class="form-control" required>
                            <option value="">-- Please choose a temple --</option>
                            @foreach($temples as $temple)
                                <option value="{{ $temple->id }}" {{ isset($selectedTemple) && $selectedTemple && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- This entire section will only appear AFTER a temple has been selected --}}
                    @if (isset($selectedTemple) && $selectedTemple)
                        {{-- THE FIX: The form action now points to the correct 'booking.details' route --}}
                        <form id="bookingForm" action="{{ route('booking.details') }}" method="POST">
                            @csrf
                            <input type="hidden" name="temple_id" value="{{ $selectedTemple->id }}">

                            {{-- 2. Multi-Month Calendar --}}
                            <div class="form-group mt-4">
                                <label>2. Select an Available Date</label>
                                <div class="flex justify-center gap-4 my-4 text-sm">
                                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-green-700"></div> Available</div>
                                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-sky-800"></div> Not Available</div>
                                    <div class="flex items-center gap-2"><div class="w-4 h-4 rounded bg-red-700"></div> Full</div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                                    @foreach ($calendars as $calendar)
                                        <div class="border rounded-lg p-3 bg-white shadow">
                                            <div class="text-center font-bold mb-2">{{ $calendar['month_name'] }}</div>
                                            <div class="grid grid-cols-7 gap-1 text-center">
                                                {{-- Day names --}}
                                                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $dayName)
                                                    <div class="font-bold text-xs text-gray-500">{{ $dayName }}</div>
                                                @endforeach
                                                {{-- Calendar days --}}
                                                @foreach ($calendar['days'] as $day)
                                                    @if (is_null($day))
                                                        <div></div>
                                                    @else
                                                        @php
                                                            $statusClass = match($day['status']) {
                                                                'available' => 'bg-green-700 text-white cursor-pointer hover:bg-green-800',
                                                                'full' => 'bg-red-700 text-white cursor-not-allowed',
                                                                'not_available' => 'bg-sky-800 text-white cursor-not-allowed',
                                                                default => '',
                                                            };
                                                        @endphp
                                                        @if ($day['status'] === 'available')
                                                            <a href="?temple_id={{ $selectedTemple->id }}&selected_date={{ $day['date'] }}" class="py-1 rounded text-sm {{ $statusClass }}">
                                                                {{ $day['day'] }}
                                                            </a>
                                                        @else
                                                            <div class="py-1 rounded text-sm {{ $statusClass }}">
                                                                {{ $day['day'] }}
                                                            </div>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- 3. Slot Selection (only shows if a date is selected) --}}
                            @if (isset($selectedDate) && $selectedDate)
                                <div class="form-group mt-4">
                                    <h4>Available Slots for: {{ $selectedDate->format('F d, Y') }}</h4>
                                    @if (!empty($slots))
                                        @foreach ($slots as $slot)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="darshan_slot_id" id="slot_{{ $slot['id'] }}" value="{{ $slot['id'] }}" required>
                                                <label class="form-check-label" for="slot_{{ $slot['id'] }}">
                                                    {{ $slot['start_time_formatted'] }} - {{ $slot['end_time_formatted'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-danger">No time slots are available for this day.</p>
                                    @endif
                                </div>

                                {{-- 4. Number of People --}}
                                <div class="form-group mt-4">
                                    <label for="number_of_people">4. Number of People</label>
                                    <input type="number" name="number_of_people" class="form-control" min="1" max="5" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Confirm Booking</button>
                            @endif
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
    // This script reloads the page with the selected temple's ID
    document.getElementById('temple_id').addEventListener('change', function() {
        if (this.value) {
            window.location.href = '/darshan-booking?temple_id=' + this.value;
        } else {
            // If the user selects the default option, reload without a temple ID
            window.location.href = '/darshan-booking';
        }
    });
</script>
@endpush
