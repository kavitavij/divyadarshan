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
