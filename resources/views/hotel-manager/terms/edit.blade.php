@extends('layouts.hotel-manager')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Edit Terms and Conditions</h3>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('hotel-manager.terms.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="row">
                <!-- Left: Editor -->
                <div class="col-md-6 mb-3">
                    <label for="terms_and_conditions" class="form-label">
                        Your Hotel's Terms and Conditions
                    </label>

                    <!-- Hidden input for Trix -->
                    <input id="terms_and_conditions" type="hidden" name="terms_and_conditions"
                        value="{{ old('terms_and_conditions', $hotel->terms_and_conditions) }}">

                    <!-- Trix editor -->
                    <trix-editor input="terms_and_conditions" class="w-100" style="min-height:300px;"></trix-editor>

                    <div class="form-text mt-2">
                        This content will be shown to users before they complete a booking (e.g., check-in/out times, cancellation, policies).
                    </div>
                </div>

                <!-- Right: Live Preview -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Live Preview</label>
                    <div id="termsPreview" class="output-box h-100" style="min-height:300px; overflow:auto;">
                        <p class="text-muted">Start typing in the editor to see preview here...</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
        </form>
    </div>
</div>
@endsection
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<style>
    .output-box {
        margin-top: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        background: #f9f9f9;
    }
</style>
@endpush
@push('scripts')
<script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const trixInput = document.getElementById("terms_and_conditions");
        const previewDiv = document.getElementById("termsPreview");

        document.addEventListener("trix-change", function (e) {
            if (e.target.inputElement.id === "terms_and_conditions") {
                let value = trixInput.value;
                if (value.trim() !== "") {
                    previewDiv.innerHTML = value;
                } else {
                    previewDiv.innerHTML = '<p class="text-muted">Start typing in the editor to see preview here...</p>';
                }
            }
        });
    });
</script>
@endpush
