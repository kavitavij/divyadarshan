<x-guest-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Page Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">User Reviews</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">See what others are saying about DivyaDarshan</p>
        </div>

        <!-- Review Submission Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-10">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Leave a Review</h2>
            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" name="name" id="name" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                @guest
                    <div class="mb-4">
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                @endguest

                <div class="mb-4">
                    <label for="rating"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rating</label>
                    <select name="rating" id="rating" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
                        <option value="">Select</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-4">
                    <label for="message"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                    <textarea name="message" id="message" rows="4" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                </div>

                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Submit
                    Review</button>
            </form>
        </div>

        <!-- Review List -->
        @forelse ($reviews as $review)
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <strong class="text-lg text-gray-800 dark:text-white">{{ $review->name }}</strong>
                    <span class="text-yellow-500 font-semibold">★ {{ $review->rating }}/5</span>
                </div>

                @if ($review->email)
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email: {{ $review->email }}</p>
                @endif

                <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $review->message }}</p>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-400">No reviews yet. Be the first to share your thoughts!</p>
        @endforelse

    </div>
    </div>
</x-guest-layout>
