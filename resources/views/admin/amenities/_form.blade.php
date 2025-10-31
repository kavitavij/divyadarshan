<div class="form-group mb-3">
    <label for="name">Amenity Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $amenity->name ?? '') }}" required>
    <small class="form-text text-muted">Example: Free WiFi</small>
</div>

<div class="form-group mb-3">
    <label for="icon">Font Awesome Icon Class</label>
    <input type="text" name="icon" class="form-control" value="{{ old('icon', $amenity->icon ?? '') }}" required>
    <small class="form-text text-muted">Example: fas fa-wifi (Find icons on FontAwesome.com)</small>
</div>

<div class="form-group mb-3">
    <label class="form-label">Or: Upload / Pick a free SVG icon</label>
    <div class="d-flex gap-2 align-items-start">
        <div>
            <input type="file" name="icon_file" id="icon_file" accept="image/svg+xml" class="form-control-file">
            <small class="form-text text-muted">Upload your own SVG (recommended) — will be used when provided.</small>
        </div>
        <div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="clearIcon">Clear</button>
        </div>
    </div>
    <div class="mt-2">
        <div id="icon-preview"
            style="width:48px;height:48px;display:inline-block;vertical-align:middle;border-radius:6px;padding:6px;background:#fff;box-shadow:0 0 0 1px rgba(0,0,0,0.04);">
            @if (!empty($amenity->icon_svg))
                {!! $amenity->icon_svg !!}
            @elseif(!empty($amenity->icon_file_path))
                <img src="{{ asset($amenity->icon_file_path) }}" alt="icon" style="max-width:100%;max-height:100%;">
            @else
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect width="24" height="24" fill="transparent" />
                </svg>
            @endif
        </div>
    </div>

    <input type="hidden" name="icon_svg" id="icon_svg" value="{{ old('icon_svg', $amenity->icon_svg ?? '') }}">

    <div class="mt-3">
        <small class="form-text text-muted">Free icon palette — click to choose:</small>
        <div class="d-flex flex-wrap gap-2 mt-2" id="free-icon-palette">
            {{-- Simple free SVG icons (Heroicons-like) --}}
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12h14" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                title="Simple Line"></button>
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>'
                title="Circle"></button>
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 12h18M12 3v18" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                title="Plus"></button>
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 12l5 5L20 7" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                title="Check"></button>
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                title="Menu"></button>
            <button type="button" class="btn btn-light btn-sm free-icon"
                data-svg='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 7h18v10H3z" stroke-linecap="round" stroke-linejoin="round"/></svg>'
                title="Box"></button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Handle SVG upload preview
        document.getElementById('icon_file')?.addEventListener('change', function(e) {
            const f = e.target.files[0];
            if (!f) return;
            if (f.type !== 'image/svg+xml') {
                alert('Please upload an SVG file.');
                e.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                const svg = ev.target.result;
                document.getElementById('icon-preview').innerHTML = svg;
                document.getElementById('icon_svg').value = svg;
            }
            reader.readAsText(f);
        });

        // Free palette selection
        document.querySelectorAll('.free-icon').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const svg = btn.getAttribute('data-svg');
                document.getElementById('icon-preview').innerHTML = svg;
                document.getElementById('icon_svg').value = svg;
                // clear file input and legacy class
                const fileInput = document.getElementById('icon_file');
                if (fileInput) fileInput.value = '';
                const faInput = document.querySelector('input[name="icon"]');
                if (faInput) faInput.value = '';
            });
        });

        document.getElementById('clearIcon')?.addEventListener('click', function() {
            document.getElementById('icon-preview').innerHTML =
                '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="24" fill="transparent"/></svg>';
            document.getElementById('icon_svg').value = '';
            const fileInput = document.getElementById('icon_file');
            if (fileInput) fileInput.value = '';
        });
    </script>
@endpush
