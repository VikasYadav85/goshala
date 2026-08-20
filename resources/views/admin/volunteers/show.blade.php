@extends('admin.layout')
@section('title', 'Volunteer · ' . $volunteer->full_name)

@section('content')

<a href="{{ route('admin.volunteers.index') }}" class="text-sm text-saffron-700 hover:text-saffron-900">← Back to volunteers</a>

<div class="grid lg:grid-cols-3 gap-6 mt-4 min-w-0">
    <div class="lg:col-span-2 admin-card p-4 sm:p-6 min-w-0">
        <h2 class="font-display text-xl font-bold mb-4">{{ $volunteer->full_name }}</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4 text-sm min-w-0">
            <dt class="text-gray-500">Email</dt><dd class="break-all min-w-0">{{ $volunteer->email }}</dd>
            <dt class="text-gray-500">Phone</dt><dd>{{ $volunteer->phone }}</dd>
            <dt class="text-gray-500">Date of birth</dt><dd>{{ optional($volunteer->date_of_birth)->format('d M Y') ?: '—' }}</dd>
            <dt class="text-gray-500">Gender</dt><dd>{{ $volunteer->gender ?: '—' }}</dd>
            <dt class="text-gray-500">City</dt><dd>{{ $volunteer->city ?: '—' }}, {{ $volunteer->state ?: '—' }}</dd>
            <dt class="text-gray-500">Occupation</dt><dd>{{ $volunteer->occupation ?: '—' }}</dd>
            <dt class="text-gray-500">Areas</dt>
            <dd>
                @foreach ((array) $volunteer->areas_of_interest as $a)
                    <span class="badge badge-info mr-1">{{ \Illuminate\Support\Str::title(str_replace('_',' ',$a)) }}</span>
                @endforeach
            </dd>
            <dt class="text-gray-500">Availability</dt>
            <dd>
                @foreach ((array) $volunteer->availability as $a)
                    <span class="badge badge-neutral mr-1">{{ \Illuminate\Support\Str::title(str_replace('_',' ',$a)) }}</span>
                @endforeach
            </dd>
            <dt class="text-gray-500">Source</dt><dd>{{ $volunteer->referral_source ?: '—' }}</dd>
        </dl>

        @if ($volunteer->previous_experience)
            <h3 class="font-display font-semibold mt-6 mb-2">Previous experience</h3>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $volunteer->previous_experience }}</p>
        @endif

        @if ($volunteer->motivation)
            <h3 class="font-display font-semibold mt-6 mb-2">Motivation</h3>
            <p class="text-sm text-gray-700 whitespace-pre-line">{{ $volunteer->motivation }}</p>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.volunteers.update', $volunteer) }}" class="admin-card p-4 sm:p-6 h-fit min-w-0">
        @csrf @method('PATCH')
        <h3 class="font-display text-lg font-bold mb-4">Status</h3>
        <label for="volunteer_status" class="form-label">Status</label>
        <select id="volunteer_status" name="status" class="form-select mb-4">
            @foreach (['pending','approved','active','inactive','rejected'] as $s)
                <option value="{{ $s }}" @selected($volunteer->status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <label for="volunteer_admin_notes" class="form-label">Admin notes</label>
        <textarea id="volunteer_admin_notes" name="admin_notes" rows="4" class="form-textarea mb-4">{{ $volunteer->admin_notes }}</textarea>
        <button class="btn btn-primary w-full text-sm">Save</button>
    </form>
</div>

@endsection
