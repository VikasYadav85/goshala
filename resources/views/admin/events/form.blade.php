@extends('admin.layout')
@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
<form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($event->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label for="event_title" class="form-label">Title *</label><input id="event_title" name="title" required value="{{ old('title', $event->title) }}" class="form-input"></div>
        <div>
            <label for="event_type" class="form-label">Type *</label>
            <select id="event_type" name="type" class="form-select">
                @foreach (['event','festival','seva','annadan','pujan'] as $t)
                    <option value="{{ $t }}" @selected(old('type', $event->type) === $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="event_status" class="form-label">Status *</label>
            <select id="event_status" name="status" class="form-select">
                @foreach (['upcoming','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $event->status) === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label for="event_starts_at" class="form-label">Starts at *</label><input id="event_starts_at" type="datetime-local" required name="starts_at" value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div><label for="event_ends_at" class="form-label">Ends at</label><input id="event_ends_at" type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div><label for="event_venue" class="form-label">Venue</label><input id="event_venue" name="venue" value="{{ old('venue', $event->venue) }}" class="form-input"></div>
        <div><label for="event_capacity" class="form-label">Capacity</label><input id="event_capacity" type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="event_address" class="form-label">Address</label><input id="event_address" name="address" value="{{ old('address', $event->address) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="event_location_url" class="form-label">Map URL</label><input id="event_location_url" type="url" name="location_url" value="{{ old('location_url', $event->location_url) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label for="event_short_description" class="form-label">Short description</label><textarea id="event_short_description" name="short_description" rows="2" class="form-textarea">{{ old('short_description', $event->short_description) }}</textarea></div>
        <div class="sm:col-span-2"><label for="event_description" class="form-label">Full description</label><textarea id="event_description" name="description" rows="6" class="form-textarea">{{ old('description', $event->description) }}</textarea></div>
        <div class="sm:col-span-2">
            <label for="event_image" class="form-label">Image</label>
            <input id="event_image" type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($event->image)<img src="{{ asset('storage/' . $event->image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>
        <div class="sm:col-span-2 space-y-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="rsvp_enabled" value="1" @checked(old('rsvp_enabled', $event->rsvp_enabled)) class="rounded"> Enable RSVP</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured)) class="rounded"> Feature on home</label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $event->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
