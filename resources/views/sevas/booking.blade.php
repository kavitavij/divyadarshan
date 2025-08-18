@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-6">Book a Seva</h1>

    <div class="max-w-3xl mx-auto">
        {{-- Temple Selector Form --}}
        <form action="{{ route('sevas.booking.index') }}" method="GET" class="mb-8 bg-white p-4 rounded-lg shadow">
            <div class="form-group">
                <label for="temple_id" class="block text-lg font-medium text-gray-700 mb-2">Please select a temple to view available sevas:</label>
                <div class="flex items-center gap-2">
                    <select name="temple_id" id="temple_id" class="form-control" onchange="this.form.submit()">
                        <option value="">-- Choose a Temple --</option>
                        @foreach($temples as $temple)
                            <option value="{{ $temple->id }}" {{ isset($selectedTemple) && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                {{ $temple->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Display Sevas for the selected temple --}}
        @if(isset($selectedTemple))
            <h2 class="text-2xl font-bold mb-4">Sevas available at {{ $selectedTemple->name }}</h2>
            <div class="space-y-4">
                @forelse($sevas as $seva)
                    <div class="bg-white p-4 rounded-lg shadow-md flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $seva->name }}</h3>
                            <p class="text-gray-600 text-sm">{{ $seva->description }}</p>
                            <p class="text-gray-800 font-bold mt-2">Price: ₹{{ number_format($seva->price, 2) }}</p>
                        </div>
                        <div>
                            <form action="{{ route('sevas.booking.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="seva_id" value="{{ $seva->id }}">
                                <button type="submit" class="btn btn-primary">Book Now</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white p-4 rounded-lg shadow-md text-center text-gray-500">
                        <p>No sevas are currently listed for this temple.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection
