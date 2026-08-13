@extends('admin.layout')
@section('title', $campaign->exists ? 'Edit campaign' : 'New campaign')
@section('page_title', $campaign->exists ? 'Edit campaign' : 'New campaign')

@section('content')

<form method="POST" action="{{ $campaign->exists ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($campaign->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="sm:col-span-2"><label class="form-label">Title *</label><input name="title" required value="{{ old('title', $campaign->title) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Short description</label><textarea name="short_description" rows="2" class="form-textarea">{{ old('short_description', $campaign->short_description) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Full description</label><textarea name="description" rows="6" class="form-textarea">{{ old('description', $campaign->description) }}</textarea></div>

        <div><label class="form-label">Goal amount (₹) *</label><input type="number" min="1" name="goal_amount" required value="{{ old('goal_amount', $campaign->goal_amount) }}" class="form-input"></div>
        <div><label class="form-label">Raised amount (₹)</label><input type="number" min="0" name="raised_amount" value="{{ old('raised_amount', $campaign->raised_amount ?: 0) }}" class="form-input"></div>
        <div><label class="form-label">Start date</label><input type="date" name="start_date" value="{{ old('start_date', optional($campaign->start_date)->format('Y-m-d')) }}" class="form-input"></div>
        <div><label class="form-label">End date</label><input type="date" name="end_date" value="{{ old('end_date', optional($campaign->end_date)->format('Y-m-d')) }}" class="form-input"></div>
        <div>
            <label class="form-label">Status *</label>
            <select name="status" class="form-select">
                @foreach (['upcoming','active','completed','emergency'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $campaign->status) === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $campaign->sort_order) }}" class="form-input"></div>

        <div class="sm:col-span-2">
            <label class="form-label">Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($campaign->image)<img src="{{ asset('storage/' . $campaign->image) }}" class="mt-2 h-32 rounded-lg" alt="">@endif
        </div>

        <div class="space-y-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_emergency" value="1" @checked(old('is_emergency', $campaign->is_emergency)) class="rounded text-saffron-600"> Mark as urgent / emergency</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $campaign->is_featured)) class="rounded text-saffron-600"> Feature on home page</label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $campaign->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>

@endsection
