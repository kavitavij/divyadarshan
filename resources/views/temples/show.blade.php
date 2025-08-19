@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- ... your temple details and tab navigation ... --}}

    {{-- Slot Booking Tab Content --}}
    <div id="slots" class="tab-content hidden">
        <form id="bookingForm" action="{{ route('booking.details') }}" method="POST">
            @csrf
            <input type="hidden" name="temple_id" value="{{ $temple->id }}">

            <div class="form-group mt-4">
                <h2 class="text-2xl font-semibold mb-4 text-center">Darshan Slot Availability</h2>
                {{-- ... your calendar grid ... --}}
            </div>

            @if ($selectedDate)
                <div class="form-group mt-4">
                    <h4>Available Slots for: {{ $selectedDate->format('F d, Y') }}</h4>
                    
                    @if ($slots->isNotEmpty())
                        @foreach ($slots as $slot)
                            <div class="form-check">
                                @if($slot->available_capacity > 0)
                                    <input class="form-check-input" type="radio" name="darshan_slot_id" id="slot_{{ $slot->id }}" value="{{ $slot->id }}" required>
                                    <label class="form-check-label" for="slot_{{ $slot->id }}">
                                        {{ $slot->start_time_formatted }} - {{ $slot->end_time_formatted }}
                                        <span class="badge bg-success">{{ $slot->available_capacity }} spots available</span>
                                    </label>
                                @else
                                    <input class="form-check-input" type="radio" name="darshan_slot_id" id="slot_{{ $slot->id }}" value="{{ $slot->id }}" disabled>
                                    <label class="form-check-label text-muted" for="slot_{{ $slot->id }}">
                                        {{ $slot->start_time_formatted }} - {{ $slot->end_time_formatted }}
                                        <span class="badge bg-danger">Full</span>
                                    </label>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger">No time slots are scheduled for this day.</p>
                    @endif
                </div>
                <div class="form-group mt-4">
                    <label for="number_of_people">Number of People</label>
                    <input type="number" name="number_of_people" class="form-control" min="1" max="5" required>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Confirm Booking</button>
            @endif
        </form>
    </div>

    {{-- ... other tab content ... --}}
</div>

<script>
    // ... your tab switching javascript ...
</script>
@endsection
