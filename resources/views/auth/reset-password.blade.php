@extends('layouts.auth')

@section('title', 'Reset Password - DivyaDarshan')

@section('content')
    <div class="min-h-screen flex relative overflow-hidden">
        {{-- Decorative Left Panel --}}
        <div class="hidden lg:flex lg:w-1/2 gradient-bg p-12 flex-col justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="absolute inset-0 overflow-hidden">
                <div class="absolute -top-40 -left-40 w-80 h-80 bg-white rounded-full opacity-20 blur-3xl"
                    style="animation: pulse 4s ease-in-out infinite;"></div>
                <div class="absolute top-1/2 -right-20 w-72 h-72 bg-blue-100 rounded-full opacity-20 blur-3xl"
                    style="animation: pulse 4s ease-in-out infinite; animation-delay: 1s;"></div>
                <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-indigo-100 rounded-full opacity-20 blur-3xl"
                    style="animation: pulse 4s ease-in-out infinite; animation-delay: 2s;"></div>
            </div>
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-16 group cursor-pointer">
                    <div
                        class="w-14 h-14 glass-effect rounded-2xl flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300 p-2">
                        <img src="{{ asset('images/logoo.png') }}" alt="DivyaDarshan Logo" class="object-contain">
                    </div>
                    <span class="text-divya-yellow text-3xl font-bold tracking-tight">DivyaDarshan</span>
                </a>
                <div class="max-w-lg">
                    <h1 class="text-white text-5xl font-bold mb-6 leading-tight">Set Your New Password</h1>
                    <p class="text-blue-100 text-lg leading-relaxed mb-10">Regain access to your account with a strong,
                        new password.</p>
                </div>
            </div>
            <div class="relative z-10 flex items-center justify-between text-blue-200 text-sm">
                <span>© {{ date('Y') }} DivyaDarshan. All rights reserved.</span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
        </div>

        {{-- Form Right Panel --}}
        <div
            class="flex-1 flex items-center justify-center p-6 lg:p-12 bg-gradient-to-br from-gray-50 via-white to-gray-50 overflow-y-auto">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex flex-col items-center justify-center mb-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg p-1">
                            <img src="{{ asset('images/logoo.png') }}" alt="DivyaDarshan Logo" class="object-contain">
                        </div>
                        <span class="text-gray-900 text-2xl font-bold text-divya-yellow">DivyaDarshan</span>
                    </div>
                </div>
                <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-lg card-shadow overflow-hidden border-0">
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                    <div class="p-8 pt-8 pb-6">
                        <div class="text-center lg:text-left mb-6">
                            <h2 class="text-gray-900 text-3xl mb-2 font-bold">Reset Password</h2>
                            <p class="text-gray-600 text-base">Enter and confirm your new password below.</p>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success mb-5">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                            @csrf

                            <!-- Password Reset Token -->
                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                    Address</label>
                                <div class="relative group">
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $request->email) }}" required autofocus
                                        autocomplete="username"
                                        class="input-focus block w-full px-3 pr-3 py-3 h-12 border border-gray-300 rounded-lg focus:outline-none transition-all text-gray-900"
                                        placeholder="name@gmail.com">
                                </div>
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative group">
                                    <input type="password" id="password" name="password" required
                                        autocomplete="new-password"
                                        class="input-focus block w-full px-3 pr-11 py-3 h-12 border border-gray-300 rounded-lg focus:outline-none transition-all text-gray-900"
                                        placeholder="Enter your new password">
                                    <button type="button" onclick="togglePassword('password', 'eye-icon-pass')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center hover:bg-blue-50 px-2 rounded-md transition-colors">
                                        <svg id="eye-icon-pass"
                                            class="h-5 w-5 text-gray-400 hover:text-blue-600 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path id="eye-icon-pass-path" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                                <div class="relative group">
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                        autocomplete="new-password"
                                        class="input-focus block w-full px-3 pr-11 py-3 h-12 border border-gray-300 rounded-lg focus:outline-none transition-all text-gray-900"
                                        placeholder="Confirm your new password">
                                    <button type="button"
                                        onclick="togglePassword('password_confirmation', 'eye-icon-conf')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center hover:bg-blue-50 px-2 rounded-md transition-colors">
                                        <svg id="eye-icon-conf"
                                            class="h-5 w-5 text-gray-400 hover:text-blue-600 transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path id="eye-icon-conf-path" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary w-full text-white font-medium py-3 h-12 rounded-lg">
                                Reset Password
                            </button>

                            <div class="text-center mt-4">
                                <a href="{{ route('login') }}"
                                    class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors">
                                    &larr; Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const eyeSlashPath =
            "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 .9-2.62 2.92-4.815 5.441-6.225M15 12a3 3 0 11-6 0 3 3 0 016 0z M2 2l20 20M17.657 4.343A12.02 12.02 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7a10.05 10.05 0 01-1.875-.175";
        const eyeOpenPath =
            "M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z";
        const eyeOpenCircle = "M15 12a3 3 0 11-6 0 3 3 0 016 0z";

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            const iconPath = document.getElementById(iconId + '-path');
            const iconCircle = icon.querySelector('path:first-child');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                iconPath.setAttribute('d', eyeSlashPath);
                iconCircle.setAttribute('d', 'M15 12a3 3 0 11-6 0 3 3 0 016 0z');
            } else {
                passwordInput.type = 'password';
                iconPath.setAttribute('d', eyeOpenPath);
                iconCircle.setAttribute('d', eyeOpenCircle);
            }
        }
    </script>
@endsection
