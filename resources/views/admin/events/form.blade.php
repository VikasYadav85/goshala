@extends('admin.layout')
@section('title', $event->exists ? 'Edit event' : 'New event')

@section('content')
<form method="POST" action="{{ $event->exists ? route('admin.events.update', $event) : route('admin.events.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($event->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label class="form-label">Title *</label><input name="title" required value="{{ old('title', $event->title) }}" class="form-input"></div>
        <div>
            <label class="form-label">Type *</label>
            <select name="type" class="form-select">
                @foreach (['event','festival','seva','annadan','pujan'] as $t)
                    <option value="{{ $t }}" @selected(old('type', $event->type) === $t)>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Status *</label>
            <select name="status" class="form-select">
                @foreach (['upcoming','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $event->status) === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Starts at *</label><input type="datetime-local" required name="starts_at" value="{{ old('starts_at', optional($event->starts_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div><label class="form-label">Ends at</label><input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($event->ends_at)->format('Y-m-d\TH:i')) }}" class="form-input"></div>
        <div><label class="form-label">Venue</label><input name="venue" value="{{ old('venue', $event->venue) }}" class="form-input"></div>
        <div><label class="form-label">Capacity</label><input type="number" name="capacity" value="{{ old('capacity', $event->capacity) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Address</label><input name="address" value="{{ old('address', $event->address) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Map URL</label><input type="url" name="location_url" value="{{ old('location_url', $event->location_url) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Short description</label><textarea name="short_description" rows="2" class="form-textarea">{{ old('short_description', $event->short_description) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Full description</label><textarea name="description" rows="6" class="form-textarea">{{ old('description', $event->description) }}</textarea></div>
        <div class="sm:col-span-2">
            <label class="form-label">Image</label>
            <input type="file" name="image" accept="image/*" class="form-input">
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
