<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        body { background-color: #f3f4f6; }
    </style>
</head>
<body class="font-sans">
    <div class="container mx-auto max-w-lg p-4 md:p-8">
        <div class="text-center mb-6">
            <a href="/" class="text-yellow-500 font-bold text-3xl">DivyaDarshan</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Booking Verification</h1>
        </div>
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p class="font-bold">Error</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">

            @if($booking->status === 'Checked-In')
                {{-- ### VIEW FOR ALREADY USED TICKET ### --}}
                <div class="p-8 text-center bg-red-600 text-white">
                    <h2 class="text-4xl font-extrabold">ALREADY USED</h2>
                    <p class="mt-2">This ticket was checked in on:</p>
                    <p class="text-lg font-semibold">{{ $booking->checked_in_at->format('d M Y, h:i A') }}</p>
                </div>
            @elseif($booking->status !== 'Confirmed')
                 {{-- ### VIEW FOR CANCELLED/INVALID TICKET ### --}}
                <div class="p-8 text-center bg-gray-600 text-white">
                    <h2 class="text-4xl font-extrabold">INVALID TICKET</h2>
                    <p class="mt-2">This ticket is currently {{ strtolower($booking->status) }} and cannot be used.</p>
                </div>
            @else
                {{-- ### VIEW FOR VALID, UNUSED TICKET ### --}}
                <div class="p-6 bg-green-600 text-white text-center">
                     <h2 class="text-3xl font-extrabold">VALID TICKET</h2>
                </div>
            @endif

            <div class="p-6 space-y-4">
                <div>
                    <h3 class="font-bold text-gray-700">Temple Name:</h3>
                    <p class="text-lg text-gray-900">{{ $booking->temple->name }}</p>
                </div>
                <hr>
                <div>
                    <h3 class="font-bold text-gray-700">Darshan Date:</h3>
                    <p class="text-lg text-gray-900">{{ \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y') }}</p>
                </div>
                <hr>
                 <div>
                    <h3 class="font-bold text-gray-700">Devotees ({{ $booking->number_of_people }}):</h3>
                    <ul class="list-disc list-inside text-lg text-gray-900">
                        @foreach($booking->devotees as $devotee)
                            <li>{{ $devotee->full_name }} (Age: {{ $devotee->age }})</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- The Action Button --}}
            @if($booking->status === 'Confirmed')
                <div class="p-6 bg-gray-50 border-t">
                    <form action="{{ route('check-in.confirm', $booking->check_in_token) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 text-white text-xl font-bold py-4 rounded-lg hover:bg-blue-700 transition-all duration-300 shadow-lg">
                            CONFIRM CHECK-IN
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
