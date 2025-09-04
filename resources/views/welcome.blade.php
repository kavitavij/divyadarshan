@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center w-full">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                <h1 class="mb-1 font-medium">Let's get started</h1>
                <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Welcome to DivyaDarshan. <br>Your spiritual journey starts here.</p>

                <ul class="flex flex-col mb-4 lg:mb-6">
                    <li class="flex items-center gap-4 py-2">
                        <span>Read the <a href="{{ route('guidelines') }}" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-blue-600">Guidelines</a></span>
                    </li>
                     <li class="flex items-center gap-4 py-2">
                        <span>Book a <a href="{{ route('booking.index') }}" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-blue-600">Darshan</a></span>
                    </li>
                </ul>
            </div>
            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                {{-- You can place a relevant image here if you like --}}
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1lg1oxA8smD7QIE6GIe5FpnnPPgZdL4_WTg&s" alt="Holy place" class="w-full h-full object-cover">
            </div>
        </main>
    </div>
@endsection
