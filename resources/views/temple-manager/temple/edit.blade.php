@extends('layouts.temple-manager')

@section('content')
    <div class="container-fluid">
        <h1>Edit Your Temple Details</h1>
        <p>Update the information for <strong>{{ $temple->name }}</strong>.</p>

        <div class="card mt-4">
            <div class="card-body">
                <form action="{{ route('temple-manager.temple.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="name">Temple Name</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name', $temple->name) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="location">Location / City</label>
                        <input type="text" name="location" class="form-control" 
                               value="{{ old('location', $temple->location) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Short Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $temple->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image">Current Image</label>
                        <div>
                            @if ($temple->image)
                                <img src="{{ asset($temple->image) }}" height="100" class="mb-2 rounded"
                                    alt="{{ $temple->name }}">
                            @endif
                        </div>
                        <label for="image">Upload New Image (optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>

                    <hr>
                    <h4 class="mt-4">Page Content Sections</h4>

                    {{-- Trix Editor with Preview --}}
                    <div class="row">
                        <!-- Left: Editor -->
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label class="form-label">About Section</label>

                            <!-- Hidden input stores Trix content -->
                            <input id="about" type="hidden" name="about" 
                                value="{{ old('about', $temple->about) }}">

                            <!-- Trix editor -->
                            <trix-editor input="about" class="w-100" style="min-height:300px;"></trix-editor>
                        </div>

                        <!-- Right: Live Preview -->
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label class="form-label">Live Preview</label>
                            <div id="aboutOutput" class="output-box h-100" style="min-height:300px; overflow:auto;">
                                <p class="text-muted">Start typing in the editor to see preview here...</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Update Details</button>
                    <a href="{{ route('temple-manager.dashboard') }}" class="btn btn-secondary mt-4">Cancel</a>
                </form>
            </div>
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
        const trixInput = document.getElementById("about");
        const outputDiv = document.getElementById("aboutOutput");

        document.addEventListener("trix-change", function (e) {
            if (e.target.inputElement.id === "about") {
                let value = trixInput.value;
                if (value.trim() !== "") {
                    outputDiv.innerHTML = value;
                } else {
                    outputDiv.innerHTML = '<p class="text-muted">Start typing in the editor to see preview here...</p>';
                }
            }
        });
    });
</script>
@endpush
