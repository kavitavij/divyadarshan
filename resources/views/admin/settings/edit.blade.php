@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Manage Website Page Content</h1>
    <p>Use the editors below to update the content for the general information pages.</p>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card card-primary card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">

                        <a class="nav-link active" id="tab-sevas-tab" data-bs-toggle="pill" href="#tab-sevas" role="tab">General Sevas</a>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link" id="tab-dress-code-tab" data-bs-toggle="pill" href="#tab-dress-code" role="tab">Dress Code</a>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link" id="tab-privacy-tab" data-bs-toggle="pill" href="#tab-privacy" role="tab">Privacy Policy</a>
                    </li>
                    <li class="nav-item">

                        <a class="nav-link" id="tab-cancellation-tab" data-bs-toggle="pill" href="#tab-cancellation" role="tab">Cancellation Policy</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    {{-- General Sevas --}}
                    <div class="tab-pane fade show active" id="tab-sevas" role="tabpanel">
                        <label for="page_content_sevas" class="form-label">Sevas Information</label>
                        <textarea name="page_content_sevas" class="wysiwyg-editor form-control">{{ old('page_content_sevas', $settings['page_content_sevas'] ?? '') }}</textarea>
                    </div>
                    {{-- Dress Code --}}
                    <div class="tab-pane fade" id="tab-dress-code" role="tabpanel">
                         <label for="page_content_dress_code" class="form-label">Dress Code Information</label>
                        <textarea name="page_content_dress_code" class="wysiwyg-editor form-control">{{ old('page_content_dress_code', $settings['page_content_dress_code'] ?? '') }}</textarea>
                    </div>
                    {{-- Privacy Policy --}}
                    <div class="tab-pane fade" id="tab-privacy" role="tabpanel">
                         <label for="page_content_privacy" class="form-label">Privacy Policy</label>
                        <textarea name="page_content_privacy" class="wysiwyg-editor form-control">{{ old('page_content_privacy', $settings['page_content_privacy'] ?? '') }}</textarea>
                    </div>
                    {{-- Cancellation Policy --}}
                    <div class="tab-pane fade" id="tab-cancellation" role="tabpanel">
                         <label for="page_content_cancellation" class="form-label">Cancellation Policy</label>
                        <textarea name="page_content_cancellation" class="wysiwyg-editor form-control">{{ old('page_content_cancellation', $settings['page_content_cancellation'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Content</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
    {{-- Make sure to use your own TinyMCE API Key --}}
    <script src="https://cdn.tiny.cloud/1/o5wfjvocpzdett1nnvnmeopwgl8i2gp5j1smdegnaukyamkf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.wysiwyg-editor',
            plugins: 'code table lists image link media preview fullscreen',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
            height: 450,
        });
    </script>
@endpush
