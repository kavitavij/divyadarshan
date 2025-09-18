@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-6 text-gray-900 dark:text-gray-100">Book a Seva</h1>

    <div class="max-w-3xl mx-auto">
        {{-- Temple Selector Form --}}
        <form action="{{ route('sevas.booking.index') }}" method="GET"
              class="mb-8 bg-white dark:bg-gray-800 p-4 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="form-group">
                <label for="temple_id" class="block text-lg font-medium text-gray-700 dark:text-gray-200 mb-2">
                    Please select a temple to view available sevas:
                </label>
                <div class="flex items-center gap-2">
                    <select name="temple_id" id="temple_id"
                        class="form-control dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600"
                        onchange="this.form.submit()">
                        <option value="">-- Choose a Temple --</option>
                        @foreach ($temples as $temple)
                            <option value="{{ $temple->id }}"
                                {{ isset($selectedTemple) && $selectedTemple->id == $temple->id ? 'selected' : '' }}>
                                {{ $temple->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        {{-- Display Sevas for the selected temple --}}
        @if (isset($selectedTemple))
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                Sevas available at {{ $selectedTemple->name }}
            </h2>
            <div class="space-y-4">
                @forelse($sevas as $seva)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center border border-gray-200 dark:border-gray-700">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100">{{ $seva->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $seva->description }}</p>
                            <p class="text-gray-800 dark:text-gray-200 font-bold mt-2">
                                Price: ₹{{ number_format($seva->price, 2) }}
                            </p>
                        </div>
                        <div>
                            <form action="{{ route('cart.addSeva') }}" method="POST">
                                @csrf
                                <input type="hidden" name="seva_id" value="{{ $seva->id }}">
                                <button type="submit" class="btn btn-success">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                        <p>No sevas are currently listed for this temple.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection

@if (session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
