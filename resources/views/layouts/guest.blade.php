<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DivyaDarshan') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        yellow: {
                            400: '#FACC15',
                            500: '#EAB308',
                            600: '#CA8A04',
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans text-gray-900 antialiased dark:bg-gray-900">
    <div class="min-h-screen flex flex-col sm:flex-row bg-gray-50 dark:bg-gray-900">

        <!-- Left Column (Blue Gradient Panel) -->
        <div
            class="w-full sm:w-1/2 md:w-5/12 p-8 md:p-12 flex flex-col justify-center bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white min-h-screen">
            <a href="/" class="flex items-center mb-6 text-2xl font-semibold">
                <img class="w-14 h-14 mr-2" src="{{ asset('images/logoo.png') }}" alt="logo">
                <span class="text-yellow-400 text-3xl font-bold">DivyaDarshan</span>
            </a>
            <h2 class="text-3xl font-bold mb-4">Welcome to DivyaDarshan</h2>
            <p class="text-lg text-blue-100 mb-8">Book your darshan, accommodation, and sevas seamlessly and securely.
            </p>

            <div class="space-y-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                        </path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-xl">Secure Bookings</h3>
                        <p class="text-blue-200">Bank-level encryption and security measures to protect your data.</p>
                    </div>
                </div>
                <div class="flex items-start mt-6">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0 mt-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-xl">Trusted by Devotees</h3>
                        <p class="text-blue-200">Rated 4.9/5 by over 10,000+ satisfied devotees worldwide.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Form Content) -->
        <div class="w-full sm:w-1/2 md:w-7/12 flex items-center justify-center p-6 md:p-12">
            <div
                class="w-full max-w-md bg-white rounded-lg shadow-xl dark:border dark:bg-gray-800 dark:border-gray-700 p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>

</html>
