@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Manage Temples</h1>
            <a href="{{ route('admin.temples.create') }}" class="btn btn-primary">Add New Temple</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    {{-- Table header remains the same --}}
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th width="500px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($temples as $temple)
                            <tr>
                                <td>{{ $temple->name }}</td>
                                <td>{{ $temple->location }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" href="{{ route('admin.temples.edit', $temple->id) }}">Edit Details</a>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#termsModal_{{ $temple->id }}">
                                        Manage T&C
                                    </button>
<a class="btn btn-warning btn-sm" href="{{ route('admin.slots.index', ['temple_id' => $temple->id]) }}">Time Slots</a>                                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.temples.sevas.index', $temple) }}">Manage Sevas</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach ($temples as $temple)
    <div class="modal fade" id="termsModal_{{ $temple->id }}" tabindex="-1" aria-labelledby="termsModalLabel_{{ $temple->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.temples.update', $temple->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- THE FIX: Add this hidden input to identify the form --}}
                    <input type="hidden" name="update_source" value="terms_modal">

                    <div class="modal-header">
                        <h5 class="modal-title" id="termsModalLabel_{{ $temple->id }}">Manage T&C for {{ $temple->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">Add, edit, or remove terms. Each line will appear as a numbered list item to the user.</p>
                        <div id="terms-container-{{ $temple->id }}">
                            @if($temple->terms_and_conditions)
                                @foreach($temple->terms_and_conditions as $term)
                                    <div class="input-group mb-2">
                                        <input type="text" name="terms_and_conditions[]" class="form-control" value="{{ $term }}">
                                        <button class="btn btn-outline-danger remove-term-btn" type="button">Remove</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline-success btn-sm mt-2 add-term-btn" data-container-id="terms-container-{{ $temple->id }}">
                            <i class="fas fa-plus"></i> Add Term
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@push('scripts')
{{-- Your JavaScript is correct and does not need changes --}}
<script>
document.addEventListener('click', function (e) {
    const addBtn = e.target.closest('.add-term-btn');
    const removeBtn = e.target.closest('.remove-term-btn');

    if (addBtn) {
        const containerId = addBtn.dataset.containerId;
        const container = document.getElementById(containerId);
        const newTermDiv = document.createElement('div');
        newTermDiv.className = 'input-group mb-2';
        newTermDiv.innerHTML = `
            <input type="text" name="terms_and_conditions[]" class="form-control" placeholder="Enter a new term">
            <button class="btn btn-outline-danger remove-term-btn" type="button">Remove</button>
        `;
        container.appendChild(newTermDiv);
    }

    if (removeBtn) {
        removeBtn.closest('.input-group').remove();
    }
});
</script>
@endpush
