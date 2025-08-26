@extends('layouts.app')

@section('title', 'Enter Devotee Details')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            {{-- Devotee Details Form --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">Enter Devotee Details</h2>
                    </div>
                    <div class="card-body">
                        <form id="devotee-form" action="{{ route('cart.addDarshan') }}" method="POST">
                            @csrf
                            {{-- Hidden fields to pass booking data along --}}
                            <input type="hidden" name="temple_id" value="{{ $bookingData['temple_id'] }}">
                            <input type="hidden" name="selected_date" value="{{ $bookingData['selected_date'] }}">
                            <input type="hidden" name="darshan_slot_id" value="{{ $bookingData['darshan_slot_id'] }}">
                            <input type="hidden" name="number_of_people" value="{{ $bookingData['number_of_people'] }}">

                            @for ($i = 0; $i < $bookingData['number_of_people']; $i++)
                                <div class="p-3 mb-3 border rounded bg-light">
                                    <h3 class="h5 mb-3">Devotee {{ $i + 1 }}</h3>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">First Name</label>
                                            <input type="text" name="devotees[{{ $i }}][first_name]"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" name="devotees[{{ $i }}][last_name]"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Age</label>
                                            <input type="number" name="devotees[{{ $i }}][age]"
                                                class="form-control" min="1" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" name="devotees[{{ $i }}][phone_number]"
                                                class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ID Type</label>
                                            <select name="devotees[{{ $i }}][id_type]" class="form-select"
                                                required>
                                                <option value="aadhar">Aadhar</option>
                                                <option value="pan">PAN Card</option>
                                                <option value="passport">Passport</option>
                                                <option value="voter_id">Voter ID</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">ID Number</label>
                                            <input type="text" name="devotees[{{ $i }}][id_number]"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            @endfor

                            <button type="submit" class="btn btn-primary w-100 mt-3">Add to Cart & Proceed</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Booking Summary --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="h5 mb-0">Booking Summary</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Temple:</strong> {{ $temple->name }}</p>
                        <p><strong>Date:</strong>
                            {{ \Carbon\Carbon::parse($bookingData['selected_date'])->format('D, M d, Y') }}</p>
                        <p><strong>Devotees:</strong> {{ $bookingData['number_of_people'] }}</p>
                        <hr>
                        <p class="fw-bold">Charge per Person: ₹{{ number_format($temple->darshan_charge, 2) }}</p>
                        <h4 class="text-success">Total:
                            ₹{{ number_format($temple->darshan_charge * $bookingData['number_of_people'], 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
