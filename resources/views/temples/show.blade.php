@extends('layouts.app')

@push('styles')
    {{-- No custom styles needed, we will use Tailwind classes directly --}}
@endpush

@section('content')
<div class="container py-5">
    <h1 class="text-3xl font-bold mb-4 text-blue-700">{{ $temple->name }}</h1>

    <div class="mb-6">
        <img src="{{ asset($temple->image) }}" alt="{{ $temple->name }}" class="w-full max-w-md rounded mb-4 mx-auto">
    </div>

    {{-- Tab Navigation --}}
    <nav class="mb-6 border-b border-gray-300">
        <ul class="flex space-x-8 justify-center flex-wrap">
            <li><a href="#about" class="tab-link border-b-2 border-blue-600 text-blue-600 pb-2 block">About</a></li>
            <li><a href="#services" class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Our Services</a></li>
            <li><a href="#slots" class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Slot Booking</a></li>
            <li><a href="#news" class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">News</a></li>
            <li><a href="#social" class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent">Social Services</a></li>
        </ul>
    </nav>

    {{-- About Tab Content --}}
    <div id="about" class="tab-content">
        <h2 class="text-2xl font-semibold mb-4">About {{ $temple->name }}</h2>
        {!! $temple->about ?: '<p>About info not available yet.</p>' !!}
    </div>

    {{-- Our Services Tab Content --}}
    <div id="services" class="tab-content hidden">
        <h2 class="text-2xl font-semibold mb-4">Our Services</h2>
        {!! $temple->online_services ?: '<p>Services information not available yet.</p>' !!}
    </div>

    {{-- Slot Booking Tab Content --}}
    <div id="slots" class="tab-content hidden">
        <form id="bookingForm" action="{{ route('booking.details') }}" method="POST">
            @csrf
            <input type="hidden" name="temple_id" value="{{ $temple->id }}">

            <div class="form-group mt-4">
                <h2 class="text-2xl font-semibold mb-4 text-center">Darshan Slot Availability</h2>
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
                                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $dayName)
                                    <div class="font-semibold text-xs text-gray-500">{{ $dayName }}</div>
                                @endforeach
                                @foreach ($calendar['days'] as $day)
                                    @if (is_null($day))
                                        <div></div>
                                    @else
                                        @php
                                            $statusClass = match($day['status']) {
                                                'available' => 'bg-green-700 text-white cursor-pointer hover:bg-green-800 transition-transform transform hover:scale-110',
                                                'full' => 'bg-red-700 text-white cursor-not-allowed',
                                                'not_available' => 'bg-sky-800 text-white cursor-not-allowed',
                                                default => '',
                                            };
                                        @endphp
                                        @if ($day['status'] === 'available')
                                            <a href="{{ route('temples.show', ['temple' => $temple->id, 'selected_date' => $day['date']]) }}#slots" class="py-1 rounded text-sm {{ $statusClass }}">
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

            @if ($selectedDate)
                <div class="form-group mt-4">
                    <h4>Available Slots for: {{ $selectedDate->format('F d, Y') }}</h4>
                    
                    @if ($slots->isNotEmpty())
                        @foreach ($slots as $slot)
                            <div class="form-check">
                                {{-- This logic now displays all slots and their live status --}}
                                @if($slot->available_capacity > 0)
                                    <input class="form-check-input" type="radio" name="darshan_slot_id" id="slot_{{ $slot->id }}" value="{{ $slot->id }}" required>
                                    <label class="form-check-label" for="slot_{{ $slot->id }}">
                                        {{ $slot->start_time_formatted }} - {{ $slot->end_time_formatted }}
                                        <span class="badge bg-success">{{ $slot->available_capacity }} slots available</span>
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
                    <input type="number" name="number_of_people" class="form-control" min="1" max="8" required>
                </div>
                <button type="submit" class="btn btn-primary mt-4">Confirm Booking</button>
            @endif
        </form>
    </div>

    {{-- News Tab Content --}}
    <div id="news" class="tab-content hidden">
        {{-- ... news content ... --}}
    </div>

    {{-- Social Services Tab Content --}}
    <div id="social" class="tab-content hidden">
        {{-- ... social services content ... --}}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        function switchTab(targetId) {
            tabLinks.forEach(link => {
                link.classList.remove('border-blue-600', 'text-blue-600');
                link.classList.add('border-transparent');
            });
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            const activeLink = document.querySelector(`.tab-link[href="${targetId}"]`);
            const activeContent = document.querySelector(targetId);

            if (activeLink) {
                activeLink.classList.add('border-blue-600', 'text-blue-600');
            }
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }

        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                history.pushState(null, null, targetId);
                switchTab(targetId);
            });
        });

        const currentHash = window.location.hash;
        if (currentHash) {
            switchTab(currentHash);
        } else {
            switchTab('#about');
        }
    });
</script>
@endsection
