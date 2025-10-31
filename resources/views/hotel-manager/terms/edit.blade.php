@extends('layouts.hotel-manager')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="mb-0">Edit Terms and Conditions</h3>
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

            <!-- Editor Section -->
            <div class="mb-4">
                <label for="terms_and_conditions" class="form-label fw-bold">
                    Your Hotel's Terms and Conditions
                </label>

                <div class="trix-wrapper border rounded p-2 bg-white">
                    <input id="terms_and_conditions" type="hidden" name="terms_and_conditions"
                        value="{{ old('terms_and_conditions', $hotel->terms_and_conditions) }}">
                    <trix-editor input="terms_and_conditions" class="w-100" style="min-height: 250px;"></trix-editor>
                </div>

                <div class="form-text mt-2">
                    This content will be shown to users before they complete a booking (e.g., check-in/out times, cancellation, policies).
                </div>
            </div>

            <!-- Live Preview Section -->
            <div class="mb-4">
                <label class="form-label fw-bold">Live Preview</label>
                <div id="termsPreview" class="output-box">
                    <p class="text-muted m-0">Start typing in the editor to see preview here...</p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
<style>
    trix-toolbar {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        display: block;
    }

    trix-editor {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        min-height: 250px;
    }

    .output-box {
        border: 1px solid #ccc;
        background: #f9f9f9;
        border-radius: 5px;
        padding: 12px;
        min-height: 250px;
        overflow-y: auto;
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
                let value = trixInput.value.trim();
                previewDiv.innerHTML = value !== ""
                    ? value
                    : '<p class="text-muted m-0">Start typing in the editor to see preview here...</p>';
            }
        });
    });
</script>
@endpush
