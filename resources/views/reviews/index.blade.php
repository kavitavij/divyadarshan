@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 dark:bg-gray-900 py-12 sm:py-16">
        <div class="container mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 dark:text-white tracking-tight">What Our Devotees Say
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 mt-4 max-w-3xl mx-auto">Real reviews from our community.
                    See how we're making a
                    difference.</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/20 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 rounded-lg relative mb-8 max-w-4xl mx-auto"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline sm:ml-2">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
                <!-- Review Submission Form -->
                <div
                    class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Leave a Review</h2>
                    <form action="{{ route('reviews.store.general') }}" method="POST">
                        @csrf
                        @guest
                            <div class="mb-4">
                                <label for="name"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Your Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-200"
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="email"
                                    class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-200"
                                    required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
                            <div
                                class="mb-4 bg-gray-100 dark:bg-gray-700/50 p-3 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-sm text-gray-700">Posting review as: <span
                                        class="font-semibold">{{ Auth::user()->name }}</span></p>
                            </div>
                        @endguest

                        <div class="mb-4">
                            <label for="review_type"
                                class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Review
                                Topic</label>
                            <select id="review_type" name="review_type"
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-200"
                                required>
                                <option value="" disabled {{ old('review_type') ? '' : 'selected' }}>Select a topic...
                                </option>
                                <option value="general" {{ old('review_type') == 'general' ? 'selected' : '' }}>General
                                </option>
                                <option value="darshan" {{ old('review_type') == 'darshan' ? 'selected' : '' }}>Darshan
                                </option>
                                <option value="seva" {{ old('review_type') == 'seva' ? 'selected' : '' }}>Seva</option>
                                <option value="accommodation"
                                    {{ old('review_type') == 'accommodation' ? 'selected' : '' }}>Accommodation</option>

                            </select>
                            @error('review_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-gray-700 dark:text-gray-300 text-sm font-medium">Your
                                    Rating</label>
                                <span id="rating-text" class="text-sm font-medium text-blue-600 dark:text-blue-400"></span>
                            </div>
                            <div class="star-rating flex flex-row-reverse justify-end items-center" id="star-container">
                                <input type="radio" id="star5" name="rating" value="5"
                                    {{ old('rating') == 5 ? 'checked' : '' }} required /><label for="star5"
                                    title="Excellent"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></label>
                                <input type="radio" id="star4" name="rating" value="4"
                                    {{ old('rating') == 4 ? 'checked' : '' }} /><label for="star4"
                                    title="Very Good"><svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></label>
                                <input type="radio" id="star3" name="rating" value="3"
                                    {{ old('rating') == 3 ? 'checked' : '' }} /><label for="star3" title="Good"><svg
                                        class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></label>
                                <input type="radio" id="star2" name="rating" value="2"
                                    {{ old('rating') == 2 ? 'checked' : '' }} /><label for="star2" title="Fair"><svg
                                        class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></label>
                                <input type="radio" id="star1" name="rating" value="1"
                                    {{ old('rating') == 1 ? 'checked' : '' }} /><label for="star1" title="Poor"><svg
                                        class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg></label>
                            </div>
                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="message"
                                class="block text-gray-700 dark:text-gray-300 text-sm font-medium mb-2">Message</label>
                            <textarea id="message" name="message" rows="5"
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-900 dark:text-gray-200"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-blue-500/30">Submit
                                Review</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Reviews -->
                <div class="space-y-6">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-2xl font-semibold text-gray-800">All Reviews ({{ $reviews->count() }})</h3>
                    </div>
                    @forelse ($reviews as $review)
                        <div
                            class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 transition-transform hover:scale-[1.02] hover:shadow-xl">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 flex items-center justify-center">
                                        <span class="text-xl font-bold">{{ substr($review->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-baseline justify-between">
                                        <div class="flex items-center gap-x-3">
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $review->name }}</h4>
                                            @if (isset($review->review_type))
                                                <span
                                                    class="inline-block bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ ucfirst($review->review_type) }}</span>
                                            @endif
                                        </div>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400 flex-shrink-0 ml-4">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex items-center my-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="w-5 h-5 {{ $i < $review->rating ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}"
                                                fill="currentColor" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        @endfor
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2 leading-relaxed">
                                        {{ $review->message }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 text-center">
                            <p class="text-gray-600 dark:text-gray-400 font-medium">No reviews yet.</p>
                            <p class="text-gray-500 mt-1">Be the first to leave one!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            padding: 0 0.1em;
            transition: transform 0.2s ease, color 0.2s ease;
            color: #d1d5db;
            /* gray-300 */
        }

        .star-rating label:hover {
            transform: scale(1.15);
        }

        .star-rating svg {
            fill: currentColor;
            width: 2rem;
            /* w-8 */
            height: 2rem;
            /* h-8 */
        }

        .star-rating input[type="radio"]:checked~label,
        .star-rating:not(:checked)>label:hover,
        .star-rating:not(:checked)>label:hover~label {
            color: #f59e0b;
            /* amber-500 */
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const starsContainer = document.getElementById('star-container');
            const ratingText = document.getElementById('rating-text');
            const ratingInputs = starsContainer.querySelectorAll('input[type="radio"]');
            const ratingLabels = starsContainer.querySelectorAll('label');

            const feedback = {
                'star1': 'Poor',
                'star2': 'Fair',
                'star3': 'Good',
                'star4': 'Very Good',
                'star5': 'Excellent'
            };

            const updateText = () => {
                const checked = starsContainer.querySelector('input[type="radio"]:checked');
                if (checked) {
                    ratingText.textContent = feedback[checked.id];
                } else {
                    ratingText.textContent = '';
                }
            };

            updateText();

            ratingLabels.forEach(label => {
                label.addEventListener('mouseenter', () => {
                    ratingText.textContent = label.title;
                });

                label.addEventListener('mouseleave', () => {
                    updateText();
                });
            });

            ratingInputs.forEach(input => {
                input.addEventListener('change', updateText);
            });
        });
    </script>
@endpush
