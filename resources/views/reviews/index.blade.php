@extends('layouts.app')

@section('content')
    <h1>Total Reviews: {{ isset($reviews) ? count($reviews) : 'No reviews found' }}</h1>

    <div class="max-w-4xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6 text-center">User Reviews</h2>

        <!-- Review Form -->
        <form action="{{ route('reviews.store') }}" method="POST" class="mb-6">
            @csrf
            <input type="text" name="name" placeholder="Your Name" class="border p-2 w-full mb-2" required>
            <select name="rating" class="border p-2 w-full mb-2" required>
                <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                <option value="4">⭐⭐⭐⭐ (4)</option>
                <option value="3">⭐⭐⭐ (3)</option>
                <option value="2">⭐⭐ (2)</option>
                <option value="1">⭐ (1)</option>
            </select>
            <textarea name="message" placeholder="Write your review..." class="border p-2 w-full mb-2" required></textarea>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Review</button>
        </form>

        <!-- Display Reviews -->
        <h3 class="text-xl font-semibold mb-4">What People Say</h3>
        <div class="space-y-4">
            @forelse ($reviews as $review)
                <div class="border rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold">{{ $review->name }}</h4>
                        <span class="text-yellow-500">
                            {{ str_repeat('⭐', $review->rating) }}
                        </span>
                    </div>
                    <p class="text-gray-600 mt-2">{{ $review->message }}</p>
                    <span class="text-xs text-gray-400">Posted on {{ $review->created_at->format('d M Y') }}</span>
                </div>
            @empty
                <p class="text-gray-500">No reviews yet. Be the first to leave one!</p>
            @endforelse
        </div>
    @endsection
