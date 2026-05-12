@extends('admin.layout')
@section('title', $faq->exists ? 'Edit FAQ' : 'New FAQ')

@section('content')
<form method="POST" action="{{ $faq->exists ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" class="admin-card p-6 max-w-2xl">
    @csrf
    @if ($faq->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="form-label">Group *</label>
            <select name="group" class="form-select">
                @foreach (['donation','volunteer','visit','tax','general'] as $g)
                    <option value="{{ $g }}" @selected(old('group', $faq->group) === $g)>{{ ucfirst($g) }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="form-label">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $faq->sort_order) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Question *</label><input name="question" required value="{{ old('question', $faq->question) }}" class="form-input"></div>
        <div class="sm:col-span-2"><label class="form-label">Answer *</label><textarea name="answer" rows="6" required class="form-textarea">{{ old('answer', $faq->answer) }}</textarea></div>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $faq->is_published ?? true)) class="rounded"> Published</label>
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary text-sm">Cancel</a>
        <button class="btn btn-primary text-sm">{{ $faq->exists ? 'Update' : 'Create' }}</button>
    </div>
</form>
@endsection
