@extends('admin.layout')
@section('title', 'Settings')

@section('content')
<x-admin.page-header title="Site settings" subtitle="Centralised values used across the public site." />

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6 max-w-3xl">
    @csrf @method('PUT')

    @forelse ($settings as $group => $rows)
        <div class="admin-card p-6">
            <h2 class="font-display text-lg font-bold mb-4 capitalize">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $group)) }}</h2>
            <div class="space-y-4">
                @foreach ($rows as $row)
                    @php($settingId = 'setting_'.$row->id)
                    <div>
                        <label for="{{ $settingId }}" class="form-label">{{ $row->label ?: \Illuminate\Support\Str::title(str_replace('_', ' ', $row->key)) }}</label>
                        @if ($row->type === 'text' || $row->type === 'html')
                            <textarea id="{{ $settingId }}" name="settings[{{ $row->key }}]" rows="3" class="form-textarea">{{ $row->value }}</textarea>
                        @elseif ($row->type === 'boolean')
                            <select id="{{ $settingId }}" name="settings[{{ $row->key }}]" class="form-select">
                                <option value="1" @selected(filter_var($row->value, FILTER_VALIDATE_BOOLEAN))>Enabled</option>
                                <option value="0" @selected(! filter_var($row->value, FILTER_VALIDATE_BOOLEAN))>Disabled</option>
                            </select>
                        @else
                            <input id="{{ $settingId }}" name="settings[{{ $row->key }}]" value="{{ $row->value }}" class="form-input">
                        @endif
                        @if ($row->description)<p class="text-xs text-gray-500 mt-1">{{ $row->description }}</p>@endif
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="admin-card p-8 text-center text-gray-500 text-sm">No settings configured. Run the seeder to populate defaults.</div>
    @endforelse

    @if ($settings->isNotEmpty())
        <div class="flex justify-end">
            <button class="btn btn-primary text-sm">Save settings</button>
        </div>
    @endif
</form>
@endsection
