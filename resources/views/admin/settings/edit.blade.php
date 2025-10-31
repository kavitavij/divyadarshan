@extends('layouts.admin')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h1 class="h3 mb-1">Manage Website Page Content</h1>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">

                            <a class="nav-link active" id="tab-sevas-tab" data-bs-toggle="pill" href="#tab-sevas"
                                role="tab">General Sevas</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link" id="tab-dress-code-tab" data-bs-toggle="pill" href="#tab-dress-code"
                                role="tab">Dress Code</a>
                        </li>
                        <li class="nav-item">
                        <li class="nav-item"></li>
                        </li>

                        <a class="nav-link" id="tab-privacy-tab" data-bs-toggle="pill" href="#tab-privacy"
                            role="tab">Privacy Policy</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link" id="tab-terms-tab" data-bs-toggle="pill" href="#tab-terms"
                                role="tab">Terms &amp; Conditions</a>
                        </li>
                        <li class="nav-item">

                            <a class="nav-link" id="tab-cancellation-tab" data-bs-toggle="pill" href="#tab-cancellation"
                                role="tab">Cancellation Policy</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        {{-- General Sevas --}}
                        <div class="tab-pane fade show active" id="tab-sevas" role="tabpanel">
                            <label for="page_content_sevas" class="form-label">Sevas Information</label>
                            <small class="text-muted d-block mb-2">Tip: select headings and use the format dropdown to apply
                                styles and colors.</small>
                            <textarea id="page_content_sevas" name="page_content_sevas" class="wysiwyg-editor form-control">{{ old('page_content_sevas', $settings['page_content_sevas'] ?? '') }}</textarea>
                            <div class="mt-2 preview-controls">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showPreview('page_content_sevas')">Show Preview</button>
                                <button type="button" class="btn btn-light btn-sm"
                                    onclick="hidePreview('page_content_sevas')">Hide Preview</button>
                                <label class="ms-2 mb-0"><input type="checkbox" id="page_content_sevas-live"> Live
                                    preview</label>
                            </div>
                            <div id="page_content_sevas-preview" class="mt-3 p-3 border bg-white"
                                style="display:none; max-height:400px; overflow:auto;"></div>
                        </div>
                        {{-- Dress Code --}}
                        <div class="tab-pane fade" id="tab-dress-code" role="tabpanel">
                            <label for="page_content_dress_code" class="form-label">Dress Code Information</label>
                            <textarea id="page_content_dress_code" name="page_content_dress_code" class="wysiwyg-editor form-control">{{ old('page_content_dress_code', $settings['page_content_dress_code'] ?? '') }}</textarea>
                            <div class="mt-2 preview-controls">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showPreview('page_content_dress_code')">Show Preview</button>
                                <button type="button" class="btn btn-light btn-sm"
                                    onclick="hidePreview('page_content_dress_code')">Hide Preview</button>
                                <label class="ms-2 mb-0"><input type="checkbox" id="page_content_dress_code-live"> Live
                                    preview</label>
                            </div>
                            <div id="page_content_dress_code-preview" class="mt-3 p-3 border bg-white"
                                style="display:none; max-height:400px; overflow:auto;"></div>
                        </div>
                        {{-- Privacy Policy --}}
                        <div class="tab-pane fade" id="tab-privacy" role="tabpanel">
                            <label for="page_content_privacy" class="form-label">Privacy Policy</label>
                            <textarea id="page_content_privacy" name="page_content_privacy" class="wysiwyg-editor form-control">{{ old('page_content_privacy', $settings['page_content_privacy'] ?? '') }}</textarea>
                            <div class="mt-2 preview-controls">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showPreview('page_content_privacy')">Show Preview</button>
                                <button type="button" class="btn btn-light btn-sm"
                                    onclick="hidePreview('page_content_privacy')">Hide Preview</button>
                                <label class="ms-2 mb-0"><input type="checkbox" id="page_content_privacy-live"> Live
                                    preview</label>
                            </div>
                            <div id="page_content_privacy-preview" class="mt-3 p-3 border bg-white"
                                style="display:none; max-height:400px; overflow:auto;"></div>
                        </div>
                        {{-- Terms & Conditions --}}
                        <div class="tab-pane fade" id="tab-terms" role="tabpanel">
                            <label for="page_content_terms" class="form-label">Terms &amp; Conditions</label>
                            <small class="text-muted d-block mb-2">You can set the effective date below; it will be shown
                                on the public Terms page alongside the content.</small>
                            <textarea id="page_content_terms" name="page_content_terms" class="wysiwyg-editor form-control">{{ old('page_content_terms', $settings['page_content_terms'] ?? '') }}</textarea>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label for="terms_effective_date" class="form-label">Effective Date</label>
                                    <input type="date" id="terms_effective_date" name="terms_effective_date"
                                        class="form-control"
                                        value="{{ old('terms_effective_date', $settings['terms_effective_date'] ?? '') }}">
                                </div>
                            </div>
                            <div class="mt-2 preview-controls">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showPreview('page_content_terms')">Show Preview</button>
                                <button type="button" class="btn btn-light btn-sm"
                                    onclick="hidePreview('page_content_terms')">Hide Preview</button>
                                <label class="ms-2 mb-0"><input type="checkbox" id="page_content_terms-live"> Live
                                    preview</label>
                            </div>
                            <div id="page_content_terms-preview" class="mt-3 p-3 border bg-white"
                                style="display:none; max-height:400px; overflow:auto;"></div>
                        </div>
                        {{-- Cancellation Policy --}}
                        <div class="tab-pane fade" id="tab-cancellation" role="tabpanel">
                            <label for="page_content_cancellation" class="form-label">Cancellation Policy</label>
                            <textarea id="page_content_cancellation" name="page_content_cancellation" class="wysiwyg-editor form-control">{{ old('page_content_cancellation', $settings['page_content_cancellation'] ?? '') }}</textarea>

                            <div class="mt-4">
                                <label for="terms_conditions" class="form-label">Terms and Conditions</label>
                                <textarea id="terms_conditions" name="terms_conditions" class="wysiwyg-editor form-control">{{ old('terms_conditions', $settings['terms_conditions'] ?? '') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <label for="privacy_policy" class="form-label">Privacy Policy</label>
                                <textarea id="privacy_policy" name="privacy_policy" class="wysiwyg-editor form-control">{{ old('privacy_policy', $settings['privacy_policy'] ?? '') }}</textarea>
                            </div>
                            <div class="mt-2 preview-controls">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    onclick="showPreview('page_content_cancellation')">Show Preview</button>
                                <button type="button" class="btn btn-light btn-sm"
                                    onclick="hidePreview('page_content_cancellation')">Hide Preview</button>
                                <label class="ms-2 mb-0"><input type="checkbox" id="page_content_cancellation-live"> Live
                                    preview</label>
                            </div>
                            <div id="page_content_cancellation-preview" class="mt-3 p-3 border bg-white"
                                style="display:none; max-height:400px; overflow:auto;"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end bg-white">
                    <a href="{{ url()->previous() }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Content</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    {{-- Make sure to use your own TinyMCE API Key --}}
    <script src="https://cdn.tiny.cloud/1/o5wfjvocpzdett1nnvnmeopwgl8i2gp5j1smdegnaukyamkf/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        // Helper functions for preview controls
        function showPreview(id) {
            const editor = tinymce.get(id);
            const content = editor ? editor.getContent() : (document.getElementById(id)?.value || '');
            const previewEl = document.getElementById(id + '-preview');
            if (previewEl) {
                // If previewing terms, include effective date if set
                if (id === 'page_content_terms') {
                    const dateInput = document.getElementById('terms_effective_date');
                    const effDate = dateInput ? dateInput.value : '';
                    const dateHtml = effDate ?
                        `<div class="mb-2 text-muted"><strong>Effective Date:</strong> ${effDate}</div>` : '';
                    previewEl.innerHTML = dateHtml + content;
                } else {
                    previewEl.innerHTML = content;
                }
                previewEl.style.display = 'block';
            }
        }

        function hidePreview(id) {
            const previewEl = document.getElementById(id + '-preview');
            if (previewEl) {
                previewEl.style.display = 'none';
            }
        }

        tinymce.init({
            selector: 'textarea.wysiwyg-editor',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount emoticons colorpicker',
            toolbar: 'undo redo | formatselect styleselect | fontselect fontsizeselect | forecolor backcolor | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | blockquote hr | link image media table | removeformat | code preview fullscreen',
            menubar: 'file edit view insert format tools table help',
            toolbar_mode: 'wrap',
            height: 520,
            skin: 'oxide',
            content_css: ['{{ asset('css/app.css') }}'],
            fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
            setup: function(editor) {
                // Update preview when editor content changes if live preview is enabled
                editor.on('Change KeyUp', function() {
                    const liveCheckbox = document.getElementById(editor.id + '-live');
                    const previewEl = document.getElementById(editor.id + '-preview');
                    if (liveCheckbox && liveCheckbox.checked && previewEl) {
                        previewEl.innerHTML = editor.getContent();
                        previewEl.style.display = 'block';
                    }
                });
            },
            // Optional: allow some advanced HTML (adjust to taste / sanitize on save)
            valid_elements: '*[*]',
            extended_valid_elements: 'iframe[src|width|height|frameborder|allow|allowfullscreen],div[*],span[*]'
        });
    </script>
@endpush
