@extends('admin.layout')
@section('title', $cow->exists ? 'Edit cow' : 'Add cow')
@section('page_title', $cow->exists ? 'Edit cow · ' . $cow->name : 'Add cow')

@section('content')

<form method="POST" action="{{ $cow->exists ? route('admin.cows.update', $cow) : route('admin.cows.store') }}" enctype="multipart/form-data" class="admin-card p-6 max-w-4xl">
    @csrf
    @if ($cow->exists) @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Name *</label>
            <input name="name" required value="{{ old('name', $cow->name) }}" class="form-input">
        </div>
        <div>
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">— None —</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $cow->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Breed</label><input name="breed" value="{{ old('breed', $cow->breed) }}" class="form-input"></div>
        <div><label class="form-label">Age</label><input name="age" value="{{ old('age', $cow->age) }}" class="form-input" placeholder="e.g. 5 years"></div>
        <div>
            <label class="form-label">Gender *</label>
            <select name="gender" class="form-select">
                <option value="female" @selected(old('gender', $cow->gender) === 'female')>Female (Gau)</option>
                <option value="male" @selected(old('gender', $cow->gender) === 'male')>Male (Nandi/Bull)</option>
            </select>
        </div>
        <div><label class="form-label">Color</label><input name="color" value="{{ old('color', $cow->color) }}" class="form-input"></div>
        <div><label class="form-label">Rescued on</label><input type="date" name="rescued_at" value="{{ old('rescued_at', optional($cow->rescued_at)->format('Y-m-d')) }}" class="form-input"></div>
        <div>
            <label class="form-label">Status *</label>
            <select name="status" class="form-select">
                @foreach (['active' => 'Active in Goshala','under_treatment' => 'Under treatment','passed_away' => 'Passed away'] as $key => $label)
                    <option value="{{ $key }}" @selected(old('status', $cow->status) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Monthly sponsorship (₹) *</label><input type="number" name="monthly_sponsorship_amount" required value="{{ old('monthly_sponsorship_amount', $cow->monthly_sponsorship_amount ?: 2100) }}" min="100" class="form-input"></div>
        <div><label class="form-label">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $cow->sort_order) }}" class="form-input"></div>

        <div class="sm:col-span-2"><label class="form-label">Rescue story</label><textarea name="rescue_story" rows="4" class="form-textarea">{{ old('rescue_story', $cow->rescue_story) }}</textarea></div>
        <div class="sm:col-span-2"><label class="form-label">Description</label><textarea name="description" rows="4" class="form-textarea">{{ old('description', $cow->description) }}</textarea></div>

        <div>
            <label class="form-label">Image</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-input">
            <p class="text-xs text-gray-500 mt-1">JPEG, PNG or WebP, max 8 MB. Stored automatically as optimized WebP.</p>
            @if ($cow->image)<img src="{{ asset('storage/' . $cow->image) }}" class="mt-2 h-24 rounded-lg" alt="">@endif
        </div>
        <div class="space-y-2 mt-2">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_available_for_sponsorship" value="1" @checked(old('is_available_for_sponsorship', $cow->is_available_for_sponsorship ?? true)) class="rounded text-saffron-600"> Available for sponsorship</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $cow->is_featured)) class="rounded text-saffron-600"> Featured on home</label>
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.cows.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $cow->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>

@endsection
