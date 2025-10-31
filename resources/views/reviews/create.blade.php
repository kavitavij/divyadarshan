@extends('layouts.app')

@section('content')
    <style>
        /* Basic style for star rating */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 0.25rem;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            font-size: 2.5rem;
            color: #d1d5db;
            /* Gray color for empty stars */
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #f59e0b;
            /* Yellow color for selected/hovered stars */
        }
    </style>

    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden">
                <div class="p-8">
                    {{-- Header --}}
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white text-center mb-2">
                        Leave a Review
                    </h1>
                    <p class="text-center text-gray-600 dark:text-gray-400 mb-6">
                        Share your experience for your stay at <strong
                            class="text-yellow-500">{{ $stayBooking->room->hotel->name }}</strong>.
                    </p>

                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                            <p class="font-bold">Please fix the following errors:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Review Form --}}
                    <form action="{{ route('reviews.store', ['stayBooking' => $stayBooking]) }}" method="POST">
                        @csrf

                        {{-- This hidden input is crucial to link the review to the booking --}}
                        <input type="hidden" name="stay_booking_id" value="{{ $stayBooking->id }}">

                        {{-- Star Rating Input --}}
                        <div class="mb-8">
                            <label
                                class="block text-lg font-semibold text-gray-700 dark:text-gray-300 text-center mb-3">Your
                                Rating</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5"
                                    {{ old('rating') == 5 ? 'checked' : '' }} required /><label for="star5"
                                    title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4"
                                    {{ old('rating') == 4 ? 'checked' : '' }} /><label for="star4"
                                    title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3"
                                    {{ old('rating') == 3 ? 'checked' : '' }} /><label for="star3"
                                    title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2"
                                    {{ old('rating') == 2 ? 'checked' : '' }} /><label for="star2"
                                    title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1"
                                    {{ old('rating') == 1 ? 'checked' : '' }} /><label for="star1"
                                    title="1 star">★</label>
                            </div>
                        </div>

                        {{-- Comment Textarea --}}
                        <div class="mb-6">
                            <label for="comment"
                                class="block text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">Your
                                Review</label>
                            <textarea name="comment" id="comment" rows="6"
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 dark:text-gray-200"
                                placeholder="Tell us about your stay...">{{ old('comment') }}</textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-8">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
