@extends('admin.layout')
@section('title', 'New invitation')

@section('content')
<form method="POST" action="{{ route('admin.invitations.store') }}" class="admin-card p-6 max-w-2xl">
    @csrf
    <p class="text-sm text-gray-500 mb-5">Fill in the guest and event details. On save, a branded invitation email (with the trust's details) is sent to the invitee.</p>

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label for="invitee_name" class="form-label">Invitee name *</label>
            <input id="invitee_name" name="invitee_name" required value="{{ old('invitee_name') }}" class="form-input">
        </div>
        <div>
            <label for="invitee_email" class="form-label">Invitee email *</label>
            <input id="invitee_email" type="email" name="invitee_email" required value="{{ old('invitee_email') }}" class="form-input">
        </div>
        <div>
            <label for="invitee_phone" class="form-label">Invitee phone</label>
            <input id="invitee_phone" name="invitee_phone" value="{{ old('invitee_phone') }}" class="form-input">
        </div>
        <div>
            <label for="occasion" class="form-label">Occasion *</label>
            <input id="occasion" name="occasion" required value="{{ old('occasion') }}" class="form-input" placeholder="e.g. Gau Puja & Annadaan">
        </div>
        <div>
            <label for="event_date" class="form-label">Event date</label>
            <input id="event_date" type="date" name="event_date" value="{{ old('event_date') }}" class="form-input">
        </div>
        <div>
            <label for="event_time" class="form-label">Event time</label>
            <input id="event_time" name="event_time" value="{{ old('event_time') }}" class="form-input" placeholder="e.g. 10:00 AM onwards">
        </div>
        <div class="sm:col-span-2">
            <label for="venue" class="form-label">Venue</label>
            <input id="venue" name="venue" value="{{ old('venue') }}" class="form-input" placeholder="Leave blank to use the goshala address">
        </div>
        <div class="sm:col-span-2">
            <label for="message" class="form-label">Personal message</label>
            <textarea id="message" name="message" rows="4" class="form-textarea" placeholder="An optional note added to the invitation">{{ old('message') }}</textarea>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.invitations.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">Send invitation</button>
    </div>
</form>
@endsection
