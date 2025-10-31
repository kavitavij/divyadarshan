@extends('layouts.auth')

@section('title', 'Login - DivyaDarshan')

@section('content')
    <div class="min-h-screen flex relative overflow-hidden">
        <div class="fixed inset-0 -z-10">
            <div
                class="absolute top-0 -left-4 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-96 h-96 bg-yellow-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
            </div>
        </div>

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
            <div
                class="absolute top-20 right-20 w-20 h-20 border-2 border-white border-opacity-20 rounded-lg rotate-12 animate-float">
            </div>
            <div
                class="absolute bottom-40 left-40 w-16 h-16 border-2 border-white border-opacity-20 rounded-full animate-float animation-delay-3000">
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
                    <h1 class="text-white text-5xl font-bold mb-6 leading-tight"> Welcome to DivyaDarshan </h1>
                    <p class="text-blue-100 text-lg leading-relaxed mb-10"> Book your darshan, accommodation, and sevas
                        seamlessly and securely. </p>
                </div>
            </div>
            <div class="relative z-10 grid grid-cols-1 gap-4 max-w-lg">
                <div
                    class="group flex items-start gap-4 p-4 glass-effect rounded-2xl transition-all duration-300 ease-in-out hover:bg-white hover:bg-opacity-[0.15] hover:scale-[1.02] hover:shadow-lg cursor-pointer">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg transition-transform duration-300 ease-in-out group-hover:scale-110">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-1.5 flex items-center gap-2"> Secure Bookings <svg
                                class="h-4 w-4 text-green-300 opacity-80 group-hover:opacity-100 transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> </h3>
                        <p class="text-blue-100 text-sm leading-relaxed"> Bank-level encryption and security measures to
                            protect your data. </p>
                    </div>
                </div>
                <div
                    class="group flex items-start gap-4 p-4 glass-effect rounded-2xl transition-all duration-300 ease-in-out hover:bg-white hover:bg-opacity-[0.15] hover:scale-[1.02] hover:shadow-lg cursor-pointer">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg transition-transform duration-300 ease-in-out group-hover:scale-110">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-semibold mb-1.5 flex items-center gap-2"> Trusted by Devotees <svg
                                class="h-4 w-4 text-yellow-300 opacity-80 group-hover:opacity-100 transition-opacity"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg> </h3>
                        <p class="text-blue-100 text-sm leading-relaxed"> Rated 4.9/5 by over 10,000+ satisfied devotees
                            worldwide. </p>
                    </div>
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
                    <p class="text-gray-600 text-sm">Seamless Darshan Booking</p>
                </div>
                <div class="bg-white bg-opacity-80 backdrop-blur-sm rounded-lg card-shadow overflow-hidden border-0">
                    <div class="h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500"></div>
                    <div class="p-8 pt-8 pb-6">
                        <div class="text-center lg:text-left mb-6">
                            <h2 class="text-gray-900 text-3xl mb-2 font-bold">Welcome Back!</h2>
                            <p class="text-gray-600 text-base">Sign in to continue to your dashboard</p>
                        </div>
                        <div class="flex bg-gray-100 p-1 rounded-xl mb-6">
                            <a href="{{ route('login') }}"
                                class="flex-1 text-center py-3 font-medium bg-white shadow-md rounded-lg text-gray-900 transition-all duration-200">
                                Login </a>
                            <a href="{{ route('register') }}"
                                class="flex-1 text-center py-3 font-medium text-gray-500 hover:text-gray-700 rounded-lg transition-all duration-200">
                                Register </a>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('status'))
                            <div class="alert alert-success"> {{ session('status') }} </div>
                        @endif

                        <button type="button" onclick="window.location='{{ route('google.redirect') }}'" <a
                            class="w-full h-12 flex items-center justify-center gap-3 border-2 border-gray-200 rounded-lg px-4 py-3 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 mb-6 font-medium text-gray-700 group relative overflow-hidden">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-50 to-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                            <svg class="w-5 h-5 relative z-10" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            <span class="relative z-10 group-hover:text-gray-900">Continue with Google</span>
                        </button>


                        <div class="relative mb-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-xs uppercase"> <span
                                    class="bg-white px-3 text-gray-500 font-medium">Or continue with email</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                    Address</label>
                                <div class="relative group">
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        required autofocus autocomplete="email"
                                        class="input-focus block w-full px-3 pr-3 py-3 h-12 border border-gray-300 rounded-lg focus:outline-none transition-all text-gray-900"
                                        placeholder="name@gmail.com">
                                </div>
                                @error('email')
                                    <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="password"
                                    class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <div class="relative group">
                                    <input type="password" id="password" name="password" required
                                        class="input-focus block w-full px-3 pr-11 py-3 h-12 border border-gray-300 rounded-lg focus:outline-none transition-all text-gray-900"
                                        placeholder="Enter your password">
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
                            <div class="flex items-center justify-between">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span
                                        class="ml-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Remember
                                        me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium hover:underline transition-colors">
                                        Forgot Password? </a>
                                @endif
                            </div>
                            <button type="submit" class="btn-primary w-full text-white font-medium py-3 h-12 rounded-lg">
                                Sign In </button>
                        </form>
                    </div>
                </div>
                <div class="lg:hidden mt-8 text-center space-y-2">
                    <p class="text-sm text-gray-500">© {{ date('Y') }} DivyaDarshan. All rights reserved.</p>
                    <div class="flex justify-center gap-4 text-sm"> <a href="#"
                            class="text-gray-600 hover:text-gray-900 transition-colors">Privacy</a> <a href="#"
                            class="text-gray-600 hover:text-gray-900 transition-colors">Terms</a> <a href="#"
                            class="text-gray-600 hover:text-gray-900 transition-colors">Help</a> </div>
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
