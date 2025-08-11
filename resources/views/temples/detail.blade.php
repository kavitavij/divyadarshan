@extends('layouts.app')

@section('content')

<h1 class="text-3xl font-bold mb-4 text-blue-700">{{ $temple->name }}</h1>

<div class="mb-6">
    <img src="{{ asset('images/temples/' . $temple->image) }}" alt="{{ $temple->name }}" class="w-full max-w-md rounded mb-4">
</div>

<nav class="mb-6 border-b border-gray-300">
    <ul class="flex space-x-8 justify-center flex-wrap">
      <li>
        <a href="#about" 
           class="tab-link border-b-2 border-blue-600 text-blue-600 pb-2 block whitespace-nowrap">
           About
        </a>
      </li>
      <li>
        <a href="#services" 
           class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent hover:border-blue-600 block whitespace-nowrap">
           Online Services
        </a>
      </li>
      <li>
        <a href="#slots" 
           class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent hover:border-blue-600 block whitespace-nowrap">
           Slot Booking
        </a>
      </li>
      <li>
        <a href="#news" 
           class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent hover:border-blue-600 block whitespace-nowrap">
           News
        </a>
      </li>
      <li>
        <a href="#social" 
           class="tab-link text-gray-600 hover:text-blue-600 pb-2 border-b-2 border-transparent hover:border-blue-600 block whitespace-nowrap">
           Social Services
        </a>
      </li>
    </ul>
</nav>

<div id="about" class="tab-content">
    <h2 class="text-2xl font-semibold mb-4">About {{ $temple->name }}</h2>
    {!! $temple->about ?: '<p>About info not available yet.</p>' !!}
</div>

<div id="services" class="tab-content hidden">
    <h2 class="text-2xl font-semibold mb-4">Online Services</h2>
    {!! $temple->online_services ?: '<p>No online services info available yet.</p>' !!}
</div>

<div id="slots" class="tab-content hidden">
    <h2 class="text-2xl font-semibold mb-4">Darshan Slot Availability (Next 4 Months)</h2>
    
    <div class="mb-4 flex justify-center space-x-6 text-sm font-semibold">
  <div class="flex items-center space-x-2">
    <span style="display:inline-block; width:20px; height:20px; background:#15803d; border-radius:0.25rem; border:1px solid #374151;"></span>
    <span>Available</span>
  </div>
  <div class="flex items-center space-x-2">
    <span style="display:inline-block; width:20px; height:20px; background:#0c4a6e; border-radius:0.25rem; border:1px solid #374151;"></span>
    <span>Not Available</span>
  </div>
  <div class="flex items-center space-x-2">
    <span style="display:inline-block; width:20px; height:20px; background:#dc2626; border-radius:0.25rem; border:1px solid #374151;"></span>
    <span>Full</span>
  </div>
</div>


    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach ($calendars as $calendar)
            <div class="p-4 border rounded shadow bg-white max-w-xs mx-auto">
                <h3 class="text-lg font-bold mb-2 text-center">{{ $calendar['month_name'] }}</h3>

                <div class="grid grid-cols-7 gap-1 text-center font-semibold mb-2 text-xs text-gray-600">
                    <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center text-xs">
                    @foreach ($calendar['days'] as $day)
                        @if (is_null($day))
                            <div></div> {{-- Empty cell --}}
                        @else
                            @php
    $bgColor = match($day['status']) {
        'available' => 'bg-green-700',
        'full' => 'bg-red-600',
        'not_available' => 'bg-sky-600',
        default => 'bg-gray-300',
    };

    // Inline fallback colors to test if Tailwind classes aren't working:
    $inlineBgColor = match($day['status']) {
        'available' => 'background-color: #15803d;',  // Tailwind green-700 hex
        'full' => 'background-color: #dc2626;',       // Tailwind red-600 hex
        'not_available' => 'background-color: #0ea5e9;', // Tailwind sky-600 hex
        default => 'background-color: #d1d5db;',       // Tailwind gray-300 hex
    };
@endphp

<div style="{{ $inlineBgColor }}" class="inline-block w-8 h-8 p-1 rounded text-white cursor-pointer" title="{{ \Carbon\Carbon::parse($day['date'])->format('l, M d, Y') }}">
    {{ $day['day'] }}
</div>

                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>


<div id="news" class="tab-content hidden">
    <h2 class="text-2xl font-semibold mb-4">News & Updates</h2>
    {!! $temple->news ?: '<p>No news available yet.</p>' !!}
</div>

<div id="social" class="tab-content hidden">
    <h2 class="text-2xl font-semibold mb-4">Social Services</h2>
    {!! $temple->social_services ?: '<p>Information about social services and community work.</p>' !!}
</div>

<script>
    document.querySelectorAll('.tab-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            document.querySelectorAll('.tab-link').forEach(el => {
                el.classList.remove('border-blue-600', 'text-blue-600');
            });

            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
            });

            this.classList.add('border-blue-600', 'text-blue-600');

            const id = this.getAttribute('href').substring(1);
            document.getElementById(id).classList.remove('hidden');
        });
    });
</script>

@endsection