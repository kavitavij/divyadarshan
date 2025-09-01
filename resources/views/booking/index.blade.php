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
        <div class="w-full lg:w-10/12">
            <div class="bg-white shadow rounded-lg">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-bold">
                        {{ isset($selectedTemple) && $selectedTemple ? 'Book Your Darshan for ' . $selectedTemple->name : 'Book Your Darshan' }}
                    </h2>
                </div>
                <div class="card-body">

                    {{-- 1. Temple Selector --}}
                    <div class="form-group">
                        <label for="temple_id">1. Select Temple</label>
                        <select name="temple_id" id="temple_id" class="form-control" required>
                            <option value="">-- Please choose a temple --</option>
                            @foreach ($temples as $temple)
                                <option value="{{ $temple->id }}"
                                    {{ isset($selectedTemple) && $selectedTemple && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                    {{ $temple->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if (isset($selectedTemple) && $selectedTemple)
                        <form id="bookingForm" action="{{ route('booking.details') }}" method="GET">
                            @csrf
                            <input type="hidden" name="temple_id" value="{{ $selectedTemple->id }}">
                            <input type="hidden" name="selected_date"
                                value="{{ $selectedDate ? $selectedDate->toDateString() : '' }}">
                            <input type="hidden" name="slot_details" id="slot_details">

                            {{-- 2. Multi-Month Calendar --}}
                            <div class="form-group mt-6">
                                <label class="block text-lg font-semibold mb-3 text-center">2. Select an Available Date</label>
                                <div class="flex justify-center gap-6 my-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded bg-green-700"></div> Available
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded bg-sky-800"></div> Not Available
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="w-4 h-4 rounded bg-red-700"></div> Full
                                    </div>
                                </div>

                                {{-- Center calendar grid --}}
                                <div class="flex justify-center">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
                                        @foreach ($calendars as $calendar)
                                            <div class="border rounded-lg p-4 bg-white shadow w-64">
                                                <div class="text-center font-bold mb-3">{{ $calendar['month_name'] }}</div>
                                                <div class="grid grid-cols-7 gap-1 text-center text-sm">
                                                    {{-- Day names --}}
                                                    @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $dayName)
                                                        <div class="font-bold text-xs text-gray-500">{{ $dayName }}</div>
                                                    @endforeach

                                                    {{-- Calendar days --}}
                                                    @foreach ($calendar['days'] as $day)
                                                        @if (is_null($day))
                                                            <div></div>
                                                        @else
                                                            @php
                                                                $statusClass = match ($day['status']) {
                                                                    'available' => 'bg-green-700 text-white cursor-pointer hover:bg-green-800',
                                                                    'full' => 'bg-red-700 text-white cursor-not-allowed',
                                                                    'not_available' => 'bg-sky-800 text-white cursor-not-allowed',
                                                                    default => '',
                                                                };
                                                            @endphp
                                                            @if ($day['status'] === 'available')
                                                                <a href="?temple_id={{ $selectedTemple->id }}&selected_date={{ $day['date'] }}"
                                                                    class="py-1 rounded block {{ $selectedDate && $selectedDate->toDateString() == $day['date'] ? 'bg-blue-600 text-white' : $statusClass }}">
                                                                    {{ $day['day'] }}
                                                                </a>
                                                            @else
                                                                <div class="py-1 rounded {{ $statusClass }}">
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
                            </div>

                            {{-- 3. Slots --}}
                            @if (isset($selectedDate) && $selectedDate)
                                <div class="form-group mt-6">
                                    <h4 class="text-lg font-semibold mb-2">Available Slots for: {{ $selectedDate->format('F d, Y') }}</h4>
                                    @if ($slots->isNotEmpty())
                                        @foreach ($slots as $slot)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="darshan_slot_id"
                                                    id="slot_{{ $slot['id'] }}" value="{{ $slot['id'] }}"
                                                    data-details="{{ $slot['time'] }}" required>
                                                <label class="form-check-label" for="slot_{{ $slot['id'] }}">
                                                    {{ $slot['time'] }}
                                                    <span class="badge bg-success">{{ $slot['capacity'] }} available</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-danger">No time slots are available for this day.</p>
                                    @endif
                                </div>

                                <div class="form-group mt-4">
                                    <label for="number_of_people">Number of People</label>
                                    <input type="number" name="number_of_people" id="number_of_people"
                                        class="form-control" min="1" max="8" required>
                                </div>

                                {{-- Darshan Charge --}}
                                <div class="form-group mt-3">
                                    <p class="font-bold text-lg text-green-700">
                                        Darshan Charge: ₹{{ number_format($selectedTemple->darshan_charge ?? 0, 2) }} per person
                                    </p>
                                    <p id="totalCharge" class="text-blue-600 font-semibold"></p>
                                </div>
                                <br>
                                <div class="flex justify-center">
                                    <button type="submit" class="animated-btn"><span>Next</span></button>
                                </div>
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
        document.getElementById('temple_id').addEventListener('change', function () {
            if (this.value) {
                window.location.href = '/darshan-booking?temple_id=' + this.value;
            } else {
                window.location.href = '/darshan-booking';
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const slotRadios = document.querySelectorAll('input[name="darshan_slot_id"]');
            const slotDetailsInput = document.getElementById('slot_details');
            if (slotRadios.length > 0 && slotDetailsInput) {
                if (!document.querySelector('input[name="darshan_slot_id"]:checked')) {
                    slotRadios[0].checked = true;
                    slotDetailsInput.value = slotRadios[0].dataset.details;
                }
                slotRadios.forEach(radio => {
                    radio.addEventListener('change', function () {
                        slotDetailsInput.value = this.dataset.details;
                    });
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const numberInput = document.getElementById('number_of_people');
            const totalChargeEl = document.getElementById('totalCharge');
            const chargePerPerson = {{ $selectedTemple->darshan_charge ?? 0 }};

            if (numberInput) {
                numberInput.addEventListener('input', function () {
                    const people = parseInt(this.value) || 0;
                    const total = people * chargePerPerson;
                    totalChargeEl.textContent = people > 0 ? `Total Charge: ₹${total.toFixed(2)}` : '';
                });
            }
        });
    </script>
@endpush
